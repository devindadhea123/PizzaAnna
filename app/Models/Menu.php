<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use App\Models\PizzaUkuran;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

    public $timestamps = true;

    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'harga',
        'ukuran',
        'gambar',
        'diskon_jenis',
        'diskon_nilai',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function pizzaUkuran()
    {
        return $this->hasMany(PizzaUkuran::class, 'id_menu', 'id_menu');
    }

    
}