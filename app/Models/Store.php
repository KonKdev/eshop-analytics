<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id',
    'url',
    'platform',
    'consumer_key',
    'consumer_secret',
    'access_token',
    'magento_token',
];


    public function user()
        {
            return $this->belongsTo(\App\Models\User::class);
        }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
