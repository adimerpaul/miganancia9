<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, softDeletes;
    protected $fillable=[
        'nit',
        'name',
        'phone',
        'email',
        'type',
        'agencia_id'
    ];
}
