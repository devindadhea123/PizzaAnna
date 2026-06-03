<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = true;

    protected $fillable = [
        'no_invoice',
        'tanggal',
        'id_kasir',
        'nama_customer',
        'no_meja',
        'total_bayar',
        'metode_bayar',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    // Relasi ke kasir (user)
    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id_user');
    }

    // Relasi ke detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }
}