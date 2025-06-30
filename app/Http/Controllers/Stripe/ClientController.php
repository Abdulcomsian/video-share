<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use app\Http\Repository\StripeService;
use App\Http\Repository\UserHandler;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{

    protected $stripeService, $userHandler;

    public function __construct(StripeService $stripeService, UserHandler $userHandler)
    {
        $this->stripeService = $stripeService;
        $this->userHandler = $userHandler;
    }

    public function createAccount()
    {

        // $user = Auth::user();

        // if($user->stripe_account_id)
        // {
        //    return response()->json(['success' => false, 'msg' => "Account Already Created"]);
        // }

        // $stripeCustomer = $this->stripeService->clientOnboarding($user);

        // $this->userHandler->updateUserByConditions([
        //     'stripe_i'
        // ])

        // dd($stripeCustomer);

        // dd($user);

        // dd($user);

    }
}
