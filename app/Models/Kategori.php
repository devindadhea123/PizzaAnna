<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = true;

    protected $fillable = [
        'nama_kategori',
        'gambar'
    ];


    // Relasi ke menu
    public function menu()
    {
        return $this->hasMany(Menu::class, 'id_kategori', 'id_kategori');
    }
}