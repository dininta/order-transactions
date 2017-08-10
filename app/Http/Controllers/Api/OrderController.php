<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Order;
use App\Model\Product;
use Illuminate\Http\Request;
use JWTAuth;

class OrderController extends Controller
{
    const DEFAULT_PER_PAGE = 10;

    protected $pageName = 'Order';

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $query = $user->orders()->paginate(static::DEFAULT_PER_PAGE);

        return response()->json([
            'status' => 200,
            'message' => $this->pageName . ' successfully fetch',
            'result' => $query->toArray()
        ]);
    }

    public function show($key)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $model = $user->orders()->find($key);

        if ($model == null) {
            return response()->json([
                'status' => 404,
                'message' => $this->pageName . ' not found',
                'result' => null
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => $this->pageName . ' successfully fetch',
            'result' => $model
        ]);
    }

    public function store(Request $request)
    {
        $form = $request->all();

        $validator = \Validator::make($form, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'phone_number' => 'required|max:50',
            'address' => 'required|max:255',
            'coupon_code' => 'string|max:20|exists:coupons,code',
            'products' => 'required',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }

        // Check product quantity
        foreach ($form['products'] as $product) {
            if (!Product::isValid($product['id'], $product['quantity'])) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Product not available'
                ]);
            }
        }

        // Check coupon validity
        if (array_key_exists('coupon_code', $form)) {
            if (!Coupon::isValid($form['coupon_code'])) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Coupon not available'
                ]);
            }
        }

        // Submit order
        \DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $order = new Order($request->all());
            $order->user_id = $user->id;
            if (array_key_exists('coupon_code', $form)) {
                $order->calculatePrice($form['products'], $form['coupon_code']);
            } else {
                $order->calculatePrice($form['products']);
            }
            $order->save();
            foreach ($form['products'] as $item) {
                $order->products()->attach([$item['id'] => ['quantity' => $item['quantity']]]);
                $product = Product::find($item['id']);
                $product->decrement('quantity', $item['quantity']);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order successfully submitted!',
            'data' => $order
        ]);
    }

    public function submitProof(Request $request)
    {
        $form = $request->all();

        $validator = \Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'payment_proof' => 'mimetypes:application/pdf,image/jpeg,image/png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors(),
                'result' => null
            ]);
        }

        $user = JWTAuth::parseToken()->authenticate();
        $order = $user->orders->find($form['order_id']);
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => $this->pageName . ' not found',
                'result' => null
            ]);
        }
        if ($order->status != Order::STATUS_VERIFIED) {
            return response()->json([
                'status' => 400,
                'message' => 'Order not verified',
                'result' => null
            ]);
        }

        $proofTemp = $request->file('payment_proof')->move('files/');
        $order->payment_proof = $proofTemp;
        $order->save();

        exec("rm " . $proofTemp->getPathname());

        return response()->json([
            'status' => 200,
            'message' => 'Payment proof successfully uploaded!',
            'result' => [
                'data' => $order
            ]
        ]);
    }
}
