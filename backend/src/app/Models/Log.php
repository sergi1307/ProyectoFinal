<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'clase',
        'accion',
        'descripcion',
        'user_id',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
