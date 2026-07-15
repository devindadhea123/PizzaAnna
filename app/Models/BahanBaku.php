<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'id_bahan';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok',
        'stok_minimal',
    ];

    public function resep()
    {
        return $this->hasMany(ResepMenu::class, 'id_bahan', 'id_bahan');
    }
}