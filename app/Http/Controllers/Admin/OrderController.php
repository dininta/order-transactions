<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Shipping;
use Illuminate\Http\Request;

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

    public function changeStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:0,1,2,3',
            'reason' => 'required_if:status,3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }

        $form = $request->all();
        $order = Order::find($form['order_id']);
        $order->status = $form['status'];
        if (array_key_exists('reason', $form)) {
            $order->reason = $form['reason'];
        }
        if ($form['status'] === Order::STATUS_SHIPPED) {
            $shipping = new Shipping();
            $order->shipping()->save($shipping);
        }
        $order->save();

        return response()->json([
            'status' => 200,
            'message' => $this->pageName . ' status successfully updated',
            'result' => $order
        ]);
    }
}
