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
        'code',
        'amount',
        'amount_type',
        'quantity',
        'start_date',
        'end_date',
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public static function isValid($code)
    {
        $coupon = Coupon::where('code', $code)->first();
        return ($coupon && Carbon::now()->between($coupon->start_date, $coupon->end_date));
    }
}
