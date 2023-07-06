<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    use HasFactory;
    protected $fillable=[
        'nombre',
        'direccion',
        'telefono',
        'email',
        'web',
        'logo',
        'color',
    ];
    public function users(){
        return $this->hasMany(User::class);
    }
    public function clientes(){
        return $this->hasMany(Client::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
