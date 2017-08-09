<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * Amount Type
     */
    const PERCENTAGE = 0;
    const NOMINAL = 1;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'amount_type',
        'quantity',
        'start_date',
        'end_date',
    ];

    public static function isValid($id)
    {
        $coupon = Coupon::find($id);
        return ($coupon && Carbon::now()->between($coupon->start_date, $coupon->end_date));
    }
}
