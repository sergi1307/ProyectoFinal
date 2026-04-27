<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'total',
        'status',
        'user_id',
        'paypal_order_id',
        'paypal_capture_id'
    ];

    public function details()
    {
        return $this->hasMany(Detail::class, 'id_order', 'id_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}