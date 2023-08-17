<?php

namespace app\Http\Repository;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Token;
use Stripe\Transfer;
use Stripe\StripeClient;

class StripeService{

    public function processPayment($request)
    {
        try{
        //setting stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
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
}