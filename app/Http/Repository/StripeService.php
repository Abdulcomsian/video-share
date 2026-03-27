<?php

namespace app\Http\Repository;

use Stripe\{Stripe, PaymentIntent, Customer, Account, AccountLink};
use App\Models\{JobProposal, User, EditorRequest};
use Illuminate\Support\Facades\Auth;
use Exception;

class StripeService{

    const PLATFORM_FEE_PERCENT = 5;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // -------------------------------------------------------
    // Client: Customer on editor's connected account
    // -------------------------------------------------------

    public function getOrCreateConnectedCustomer(User $client, string $stripeAccountId): Customer
    {
        // Check if customer already exists on the editor's connected account
        $existing = Customer::all([
            'email' => $client->email,
            'limit' => 1,
        ], ['stripe_account' => $stripeAccountId]);

        if ($existing->data && count($existing->data) > 0) {
            return $existing->data[0];
        }

        // Create a new customer on the editor's connected account
        return Customer::create([
            'email' => $client->email,
            'name' => $client->full_name,
            'metadata' => ['user_id' => $client->id],
        ], ['stripe_account' => $stripeAccountId]);
    }

    // -------------------------------------------------------
    // Payment: Direct charge on editor's connected account
    // -------------------------------------------------------

    public function createPaymentIntent($request)
    {
        try {
            $requestId = $request->request_id;

            $jobProposal = JobProposal::findOrFail($requestId);

            // Find the editor's connected account via the editor request
            $editorRequest = EditorRequest::where('request_id', $requestId)->firstOrFail();
            $editor = User::findOrFail($editorRequest->editor_id);

            if (!$editor->stripe_account_id) {
                return ['success' => false, 'error' => 'Editor has not completed Stripe onboarding'];
            }

            // Get or create customer on the editor's connected account
            $client = auth()->user();
            $connectedCustomer = $this->getOrCreateConnectedCustomer($client, $editor->stripe_account_id);

            $bidAmount = $jobProposal->bid_price;
            $amountInCents = (int) round($bidAmount * 100);
            $platformFeeInCents = (int) round($bidAmount * self::PLATFORM_FEE_PERCENT);

            // Direct charge on editor's connected account
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'customer' => $connectedCustomer->id,
                'payment_method_types' => ['card'],
                'capture_method' => 'manual',
                'application_fee_amount' => $platformFeeInCents,
                'description' => "Job payment for proposal #{$requestId}",
                'metadata' => [
                    'job_proposal_id' => $requestId,
                    'client_id' => $client->id,
                    'editor_id' => $editor->id,
                ],
            ], [
                'stripe_account' => $editor->stripe_account_id,
            ]);

            return [
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'stripe_account_id' => $editor->stripe_account_id,
                'bid_amount' => $bidAmount,
                'platform_fee' => round($bidAmount * self::PLATFORM_FEE_PERCENT / 100, 2),
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function capturePayment(string $paymentIntentId, string $editorStripeAccountId)
    {
        return PaymentIntent::retrieve($paymentIntentId, [
            'stripe_account' => $editorStripeAccountId,
        ])->capture();
    }

    public function cancelPayment(string $paymentIntentId, string $editorStripeAccountId)
    {
        return PaymentIntent::retrieve($paymentIntentId, [
            'stripe_account' => $editorStripeAccountId,
        ])->cancel();
    }

    // -------------------------------------------------------
    // Stripe publishable key for Flutter client
    // -------------------------------------------------------

    public function getPublishableKey()
    {
        return ['success' => true, 'publicKey' => config('services.stripe.public')];
    }

    // -------------------------------------------------------
    // Editor: Stripe Connect onboarding
    // -------------------------------------------------------

    public function editorOnboarding()
    {
        $user = Auth::user();

        if ($user->onboarding) {
            throw new Exception("Onboarding is already verified", 1);
        }

        if (!$user->stripe_account_id) {
            $account = Account::create([
                'type' => 'custom',
                'tos_acceptance' => [
                    'date' => time(),
                    'ip' => request()->ip(),
                ],
                'capabilities' => [
                    'transfers' => ['requested' => true],
                    'card_payments' => ['requested' => true],
                ],
            ]);

            User::where('id', $user->id)->update(['stripe_account_id' => $account->id]);
            $stripeAccountId = $account->id;
        } else {
            $stripeAccountId = $user->stripe_account_id;
        }

        $accountLink = $this->createEditoryOnboardingAccountLink($stripeAccountId);

        $user->refresh();

        return [
            'user' => $user->toArray(),
            'acccount_link' => $accountLink,
        ];
    }

    public function createEditoryOnboardingAccountLink($stripeAccountId)
    {
        return AccountLink::create([
            'account' => $stripeAccountId,
            'refresh_url' => route('stripe.refresh', ['account_id' => $stripeAccountId]),
            'return_url' => route('stripe.success', ['account_id' => $stripeAccountId]),
            'type' => 'account_onboarding',
        ]);
    }

    public function getEditorStripeDetailsById($stripeId)
    {
        return Account::retrieve($stripeId);
    }

    public function getAllEditorExternalAccountsByAccountType(string $merchantStripeId, ?array $params = null)
    {
        $params['limit'] = $params['limit'] ?? 100;
        return Account::allExternalAccounts($merchantStripeId, $params);
    }

}
