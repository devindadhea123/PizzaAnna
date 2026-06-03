<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Topping;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pesanan',
        'id_menu',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'ukuran'
    ];

    // ================= AUTO SUBTOTAL =================
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->subtotal = $model->jumlah * $model->harga_satuan;
        });
    }

    // ================= RELASI =================

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function toppings()
    {
        return $this->belongsToMany(
            Topping::class,
            'detail_pesanan_topping',
            'detail_pesanan_id',
            'topping_id'
        );
    }
}