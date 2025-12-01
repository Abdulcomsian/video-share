<?php

namespace app\Http\Repository;

use App\Http\AppConst;
use Stripe\{Stripe, Charge, Token, Transfer, StripeClient, PaymentIntent, Customer, Account, AccountLink};
use App\Models\{JobPayment, PersonalJob, JobProposal, User};
use Illuminate\Support\Facades\Auth;
use Exception;

class StripeService{

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function processPayment($request)
    {
        try{
            // $amount = $request->amount;
            JobPayment::where('request_id' , $request->request_id)
                        ->where('job_id' , $request->job_id)
                        ->update(['client_transfer_status' => AppConst::CLIENT_PAYED , 'client_payment_date' => date("Y-m-d")]);

            return ['success' => true , 'msg' => 'Payment Processed Successfully' ];

        }catch(\Exception $e){
            return ['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $e->getMessage()];
        }
    }

    public function getPublishableKey(){
        $publicKey = env('STRIPE_KEY');
        return ['success' => true , 'publicKey' => $publicKey];
    }


    public function createPaymentIntent($request){
        try{

            // $jobId  = $request->job_id;
            $requestId = $request->request_id;



            // $jobDetail = PersonalJob::with('awardedRequest.proposal')->where('id' , $jobId)->first();

            // if($jobDetail->status == "unawarded"){
            //     return ["success" => false , 'msg' => "Something Went Wrong" , "error" => "Job Must Be Awarded First"];
            // }
            $jobProposal = JobProposal::where('id' , $requestId)->first();

            // Stripe::setApiKey(env('STRIPE_SECRET'));
            $amount = $jobProposal->bid_price * 100;
            $currency = 'usd';

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'payment_method_types' => ['card'],
                'capture_method' => 'manual', // This holds the payment
                'description' => 'Job award payment hold for editor',
                'metadata' => [
                    'job_proposal_id' => $requestId,
                    'client_id' => auth()->id(),
                ],
            ]);


            return ['success' => true , 'clientSecret' => $paymentIntent->client_secret, 'payment_intent_id' => $paymentIntent->id];

        }catch(\Exception $e){

            return ['success' => false , 'error' => $e->getMessage()];

        }

    }

    public function partialPayment($request){
        try{
            //setting stripe secret key
            // Stripe::setApiKey(env('STRIPE_SECRET'));

            //creating token
            $token = Token::create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->card_exp_month,
                    'exp_year' => $request->card_exp_year,
                    'cvc' => $request->cvc,
                ],
            ]);

            $amount = $request->amount;

            $platformCommission = $request->amount * 0.05;


            $charge = Charge::create([
                'amount' => $platformCommission * 100, // Stripe accepts amounts in cents
                'currency' => 'usd',
                'source' => $token->id,
                'description' => 'Payment for service',
            ]);

            $editorAmount = $amount - $platformCommission;

            // $EDITOR_STRIPE_ACCOUNT_ID = 4000000000000077;
            // Create a transfer to the employee's Stripe account

            $stripeConnectedAccount = new StripeClient(env('STRIPE_SECRET'));

            $account = $stripeConnectedAccount->accounts->create([
                'type' => 'standard',
                'default_currency' => 'usd',
                'email' => 'rajashayzee@yahoo.com'
            ]);

            $accountId = $account->id;


            $transfer = Transfer::create([
                'amount' => $editorAmount * 100,
                'currency' => 'usd',
                'destination' => $accountId,
                'transfer_group' => 'ORDER_'.$charge->id,
            ]);

            dd( "Hrere bossss", $transfer);
            }catch(\Exception $e){
                dd($e->getMessage());
            }
    }


    public function paymentIntent($request){
        try{
            // $amount = $request->amount;
            $jobRequest = JobProposal::where('id' , $request->request_id)->first();

            //creating token
            //token code starts here
            //Stripe::setApiKey(env('STRIPE_SECRET'));

            // $token = Token::create([
            //     'card' => [
            //         'number' => $request->card_number,
            //         'exp_month' => $request->card_exp_month,
            //         'exp_year' => $request->card_exp_year,
            //         'cvc' => $request->cvc,
            //     ],
            // ]);

            // $charge = Charge::create([
            //     'amount' => $jobRequest->bid_price * 100, // Stripe accepts amounts in cents
            //     'currency' => 'usd',
            //     'source' => $token->id,
            //     'description' => 'Payment for service',
            // ]);

            //token code ends here
            $stripe = new StripeClient(env('STRIPE_SECRET'));

            $charge = $stripe->paymentIntents->create([
                'amount' => 99 * 100,                   // Amount in cents (in this case, $99)
                'currency' => 'usd',                   // Currency (US dollars)
                'payment_method' => $request->payment_method,// provide id of payment
                'description' => 'test stripe ',
                'confirm' => true,                     // Confirm the payment immediately
                'receipt_email' => $request->email     // Email address for receipt
            ]);

            if($charge->status == 'succeeded'){
                JobPayment::where('request_id' , $request->request_id)
                            ->where('job_id' , $request->job_id)
                            ->update(['client_transfer_status' => AppConst::CLIENT_PAYED , 'client_payment_date' => date("Y-m-d")]);
                return ['success' => true , 'msg' => 'Payment Processed Successfully' , 'detail' => $charge];
            }else{
                return ['success' => false , 'msg' => 'Something Went Wrong' , 'detail' => $charge];
            }


        }catch(\Exception $e){
            return ['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $e->getMessage()];
        }
    }

    public function clientOnboarding($data)
    {
        return Customer::create([
            'email' => $data->email,
            'name' => $data->full_name
        ]);

    }

    public function editorOnboarding($data)
    {

        $user = Auth::user();
        if ($user->onboarding) {
            throw new Exception("Onboarding is already verified", 1);
        }

        if (!$user->stripe_account_id) {

            $data = [
                'type' => 'custom',
                'tos_acceptance' => [
                    'date' => time(), // Timestamp of acceptance
                    'ip' => request()->ip(), // IP address of user
                ],
                'capabilities' => [
                    'transfers' => ['requested' => true],  // Enables transfer capability (withdrawing funds)
                    'card_payments' => ['requested' => true],  // Enables card payment capability (receiving payments via card)
                ],
            ];

            //create stripe-connect(onbaording) account
            $account = Account::create($data);

            // update user stripe ID
            User::where('id', $user->id)->update(['stripe_account_id' => $account->id]);

            $stripeAccountId = $account->id;

        } else {
            $stripeAccountId = $user->stripe_account_id;
        }

        // creat onboarding account link
        $accountLink = $this->createEditoryOnboardingAccountLink($stripeAccountId);

        // refresh to update user data
        $user->refresh();

        return [
            'user' => $user->toArray(),
            'acccount_link' => $accountLink
        ];

    }

    public function createEditoryOnboardingAccountLink($stripeAccountId)
    {
        $accountLink = AccountLink::create([
            'account' => $stripeAccountId,
            'refresh_url' => route('stripe.refresh', ['account_id' => $stripeAccountId]),
            'return_url' => route('stripe.success', ['account_id' => $stripeAccountId]),
            'type' => 'account_onboarding',
        ]);

        return $accountLink;
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

    public function capturedPayment($paymentIntentId)
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        return PaymentIntent::retrieve($paymentIntentId)->capture();
    }

    public function reversePayment($paymentIntentId)
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        return PaymentIntent::retrieve($paymentIntentId)->cancel();
    }

}
