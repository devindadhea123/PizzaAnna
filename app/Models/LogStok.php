<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    protected $table = 'log_stok';
    protected $primaryKey = 'id_log_stok';

    protected $fillable = [
        'id_bahan',
        'stok_sebelum',
        'stok_sesudah',
        'perubahan',
        'keterangan',
        'tipe',
        'referensi',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan', 'id_bahan');
    }
}
