<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Repository\{StripeService, UserHandler};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class EditorController extends Controller
{
    protected $stripeService, $userHandler;

    public function __construct(StripeService $stripeService, UserHandler $userHandler)
    {
        $this->stripeService = $stripeService;
        $this->userHandler = $userHandler;
    }

    public function createAccount(Request $request)
    {
        try {

            // validated data
            $data = $request->all();

            $data['account_type'] = 'individual';

            // create merchant account
            $data = $this->stripeService->editorOnboarding($data);

            return response()->json(['success' => true , 'msg' => "Onboarding link generated successfully" , "data" => $data] ,200);


        } catch (Exception $e) {

            return response()->json(['success' => false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    // public function checkOnboardingStatus() : JsonResponse
    // {
    //     try {

    //         // create merchant account
    //         $data = $this->merchantService->checkOnboardingStatus();

    //         return ApiResponse::success($data, 'Account onboarding status retrived successfully.');
    //     } catch (Exception $e) {

    //         return ApiResponse::error('Something went wrong', ['error' => $e->getMessage()], 500);
    //     }
    // }


    // public function connectPaymentMethod(MerchantPaymentMethodConnectRequest $request): JsonResponse
    // {

    //     try {

    //         // validated data
    //         $validatedData = $request->validated();

    //         // create connect merchant payment method DTO
    //         $connectPaymentMethodDTO = new ConnectMerchantPaymentMethodDTO($validatedData);

    //         // connect payment method with merchant
    //         $this->merchantService->connectPaymentMethod($connectPaymentMethodDTO);

    //         return ApiResponse::success(null, 'Payment Method has connected with merchant successfully');
    //     } catch (Exception $e) {

    //         return ApiResponse::error('Something went wrong', ['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function getAllMerchantExternalAccounts(GetMerchantExternalAccountsRequest $request) : JsonResponse
    // {
    //     try {

    //         // validated data
    //         $validatedData = $request->validated();

    //         $type = $validatedData['type'] ?? '';

    //         // get all payment methods of merchant
    //         $data = $this->merchantService->getAllPaymentMethods($type);

    //         // Check if $data is a collection or array and count its items
    //         if (count($data) === 0) {
    //             return ApiResponse::success(null, 'No payment methods found');
    //         }

    //         return ApiResponse::success($data, 'Customer Payment Methods has retrived successfully');

    //     } catch (Exception $e) {

    //         return ApiResponse::error('Something went wrong', ['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function setPaymentMethodAsDefault(MerchantSetDefaultPaymentMethodRequest $request) : JsonResponse
    // {

    //     try {

    //         // validated data
    //         $validatedData = $request->validated();

    //         // create connect merchant payment method DTO
    //         $merchantDefaultPaymentMethodDTO = new SetDefaultMerchantPaymentMethodDTO($validatedData);

    //         // connect payment method with merchant
    //         $this->merchantService->setPaymentMethodAsDefault($merchantDefaultPaymentMethodDTO);

    //         return ApiResponse::success(null, 'Merchant Payment Method set as default successfully');
    //     } catch (Exception $e) {

    //         return ApiResponse::error('Something went wrong', ['error' => $e->getMessage()], 500);
    //     }

    // }


}
