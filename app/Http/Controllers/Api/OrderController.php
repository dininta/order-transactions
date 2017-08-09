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
        $query = Order::paginate(static::DEFAULT_PER_PAGE);

        return response()->json([
            'status' => 200,
            'message' => $this->pageName . ' successfully fetch',
            'result' => $query->toArray()
        ]);
    }

    public function show($key)
    {
        $model = Order::find($key);

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
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'phone_number' => 'required|max:50',
            'address' => 'required|max:255',
            'coupon_id' => 'integer|exists:coupons,id',
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
        foreach ($request->all()['products'] as $product) {
            if (!Product::isValid($product['id'], $product['quantity'])) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Product not available'
                ]);
            }
        }

        // Check coupon validity
        if (array_key_exists('coupon_id', $request->all())) {
            $id = $request->all()['coupon_id'];
            if (!Coupon::isValid($id)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Coupon not available'
                ]);
            }
        }

        // Calculate total price
        $total = 0;
        foreach ($request->all()['products'] as $item) {
            $product = Product::find($item['id']);
            $total += $product->price * $item['quantity'];
        }
        if (array_key_exists('coupon_id', $request->all())) {
            if ($coupon->amount_type == Coupon::PERCENTAGE) {
                $total *= 1 - $coupon->amount;
            } else {
                $total -= $coupon->amount;
            }
        }

        // Submit order
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $order = new Order($request->all());
        $order->user_id = $user->id;
        $order->calculatePrice($request->all());
        $order->save();
        foreach ($request->all()['products'] as $item) {
            $order->products()->attach([$item['id'] => ['quantity' => $item['quantity']]]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order successfully submitted!',
            'data' => $order
        ]);
    }
}
