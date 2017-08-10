<?php

namespace App\Http\Controllers\Api;

use App\Model\Shipping;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Response;

class ShippingController extends Controller
{
    protected $pageName = 'Shipping';

    public function checkStatus(Request $request)
    {
        $form = $request->all();

        $validator = \Validator::make($request->all(), [
            'shipping_id' => 'required|string|max:20|exists:shippings,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors(),
                'result' => null
            ]);
        }

        $user = JWTAuth::parseToken()->authenticate();
        $shipping = Shipping::find($form['shipping_id']);
        if (!$shipping || $shipping->order->user_id !== $user->id) {
            return response()->json([
                'status' => 404,
                'message' => $this->pageName . ' not found',
                'result' => null
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => $this->pageName . ' successfully fetch',
            'result' => [
                'data' => $shipping
            ]
        ]);
    }
}
