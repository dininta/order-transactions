<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
    ];

    public static function isValid($id, $quantity)
    {
        $product = Product::find($id);
        return ($product->quantity > $quantity);
    }
}
