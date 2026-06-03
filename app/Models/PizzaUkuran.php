<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaUkuran extends Model
{
    use HasFactory;

    protected $table = 'pizza_ukuran';

    // INI YANG BENAR
    protected $primaryKey = 'id_ukuran';

    protected $fillable = [
        'id_menu',
        'ukuran',
        'harga',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}