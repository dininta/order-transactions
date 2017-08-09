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

    public function calculatePrice($form)
    {
        $total = 0;
        foreach ($form['products'] as $item) {
            $total += Product::find($item['id'])->price * $item['quantity'];
        }
        if (array_key_exists('coupon_id', $form)) {
            $coupon = Coupon::find($form['coupon_id']);
            if ($coupon->amount_type == Coupon::PERCENTAGE) {
                $total *= 1 - $coupon->amount;
            } else {
                $total -= $coupon->amount;
            }
        }

        $this->total_price = $total;
    }
}
