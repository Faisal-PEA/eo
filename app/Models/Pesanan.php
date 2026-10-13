<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke User (customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Paket
    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    // Relasi ke Addons melalui tabel pivot pesanan_addons
    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'pesanan_addons', 'pesanan_id', 'addon_id')
                    ->withPivot('qty', 'subtotal')
                    ->withTimestamps();
    }

    // Relasi ke Pembayarans
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}