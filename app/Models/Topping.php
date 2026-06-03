<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    protected $table = 'toppings';

    protected $primaryKey = 'id_topping';

    protected $fillable = [
        'nama_topping',
        'ukuran',
        'harga',
    ];
}