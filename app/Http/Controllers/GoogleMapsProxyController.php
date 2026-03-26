<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GoogleMapsProxyController extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('app.google_location_api_token');
    }

    public function autocomplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input' => 'required|string|max:255',
            'language' => 'nullable|string|max:10',
            'components' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => 'Invalid parameters', 'error' => $validator->getMessageBag()], 400);
        }

        $params = [
            'input' => $request->input('input'),
            'key' => $this->apiKey,
        ];

        if ($request->filled('language')) {
            $params['language'] = $request->input('language');
        }
        if ($request->filled('components')) {
            $params['components'] = $request->input('components');
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', $params);

        return response()->json(['success' => true, 'data' => $response->json()]);
    }

    public function placeDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place_id' => 'required|string|max:300',
            'fields' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => 'Invalid parameters', 'error' => $validator->getMessageBag()], 400);
        }

        $params = [
            'place_id' => $request->input('place_id'),
            'key' => $this->apiKey,
        ];

        if ($request->filled('fields')) {
            $params['fields'] = $request->input('fields');
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', $params);

        return response()->json(['success' => true, 'data' => $response->json()]);
    }

    public function geocode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required_without:latlng|string|max:500',
            'latlng' => 'required_without:address|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg' => 'Invalid parameters', 'error' => $validator->getMessageBag()], 400);
        }

        $params = ['key' => $this->apiKey];

        if ($request->filled('address')) {
            $params['address'] = $request->input('address');
        }
        if ($request->filled('latlng')) {
            $params['latlng'] = $request->input('latlng');
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', $params);

        return response()->json(['success' => true, 'data' => $response->json()]);
    }
}
