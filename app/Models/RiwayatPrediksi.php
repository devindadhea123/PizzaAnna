<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPrediksi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_prediksi';
    protected $primaryKey = 'id_prediksi';
    public $timestamps = true;

    protected $fillable = [
        'tanggal_prediksi',
        'bulan_target',
        'data_yang_dipakai',
        'hasil_prediksi',
        'rekomendasi_promosi',
        'rata_rata_akurasi',
        'detail_akurasi'
    ];

    protected $casts = [
        'tanggal_prediksi' => 'datetime',
        'hasil_prediksi' => 'array',        
        'rekomendasi_promosi' => 'array',   
        'detail_akurasi' => 'array'
    ];
    public function getRekomendasiPromosiAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        $decoded2 = json_decode($decoded, true);
        return is_array($decoded2) ? $decoded2 : [];
    }
    
    public function getHasilPrediksiAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        
        $decoded2 = json_decode($decoded, true);
        return is_array($decoded2) ? $decoded2 : [];
    }

     public static function hitungAkurasi($prediksi, $aktual)
    {
        if ($aktual == 0) return 0;
        
        $selisih = abs($prediksi - $aktual);
        $akurasi = (1 - ($selisih / $aktual)) * 100;
        
        return round($akurasi, 1);
    }

    public static function getLabelAkurasi($akurasi)
    {
        if ($akurasi >= 90) {
            return ['text' => 'Sangat Akurat', 'class' => 'bg-green-100 text-green-700', 'icon' => '🟢'];
        } elseif ($akurasi >= 80) {
            return ['text' => 'Akurat', 'class' => 'bg-yellow-100 text-yellow-700', 'icon' => '🟡'];
        } elseif ($akurasi >= 70) {
            return ['text' => 'Cukup Akurat', 'class' => 'bg-orange-100 text-orange-700', 'icon' => '🟠'];
        } else {
            return ['text' => 'Kurang Akurat', 'class' => 'bg-red-100 text-red-700', 'icon' => '🔴'];
        }
    }

}