<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepMenu extends Model
{
    protected $table = 'resep_menu';
    protected $primaryKey = 'id_resep';

    protected $fillable = [
        'id_menu',
        'id_bahan',
        'ukuran',
        'jumlah',
        'satuan',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan', 'id_bahan');
    }
}