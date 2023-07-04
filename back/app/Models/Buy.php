<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buy extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'client_id',
        'product_id',
        'unit',
        'description',
        'unitQuantity',
        'quantity',
        'price',
        'total',
        'user_id',
        'dateExpiration',
        'canceled',
        'canceledBy',
    ];
}
