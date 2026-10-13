<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke Pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Accessor Fleksibel untuk Nominal Pembayaran
     */
    public function getNominalAttribute()
    {
        return $this->jumlah ?? $this->jumlah_bayar ?? 0;
    }

    /**
     * Accessor Fleksibel untuk Tipe Pembayaran
     */
    public function getTipeAttribute()
    {
        return $this->attributes['tipe'] ?? $this->attributes['tipe_pembayaran'] ?? 'dp';
    }
}