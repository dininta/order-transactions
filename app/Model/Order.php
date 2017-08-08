<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status',
        'reason',
        'payment_proof',
        'name',
        'email',
        'phone_number',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
