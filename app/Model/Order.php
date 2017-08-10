<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Order Status
     */
    const STATUS_WAITING = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_SHIPPED = 2;
    const STATUS_CANCELED = 3;

    protected $fillable = [
        'status',
        'reason',
        'payment_proof',
        'coupon_id',
        'name',
        'email',
        'phone_number',
        'address',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->status = static::STATUS_WAITING;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot('quantity');
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function calculatePrice($products, $couponCode = null)
    {
        $total = 0;
        foreach ($products as $product) {
            $total += Product::find($product['id'])->price * $product['quantity'];
        }
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon->amount_type == Coupon::PERCENTAGE) {
                $total *= 1 - $coupon->amount/100;
            } else {
                $total -= $coupon->amount;
            }
        }

        $this->total_price = (int) $total;
    }
}
