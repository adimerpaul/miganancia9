<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Sale extends Model
{
    use HasFactory, softDeletes;
    protected $fillable=[
        'client_id',
        'user_id',
        'agencia_id',
        'total',
        'date',
        'time',
        'dateTime',
        'type',
        'description',
        'canceled',
        'canceledBy',
        'clientName',
    ];
    public function details(){
        return $this->hasMany(Detail::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
