<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
    use HasFactory, softDeletes;
    protected $fillable=[
        'sale_id',
        'user_id',
        'product_id',
        'product_id',
        'price',
        'quantity',
        'total',
        'productName',
    ];
}
