<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
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
}
