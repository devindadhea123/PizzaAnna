<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'harga',
        'stok_menu',
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
    
    public function resep()
    {
        return $this->hasMany(ResepMenu::class, 'id_menu', 'id_menu');
    }
}