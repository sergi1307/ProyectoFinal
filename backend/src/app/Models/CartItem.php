<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $primaryKey = 'id_cart_item';

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id_cart');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
