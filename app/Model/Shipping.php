<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
	const DEFAULT_STATUS = 'Dropped at logistic partner';

    protected $fillable = [
        'status',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = "SH-" . sprintf("%010d", $model->order_id);
            $model->status = static::DEFAULT_STATUS;
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
