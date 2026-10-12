<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function pesananAddons()
    {
        return $this->hasMany(PesananAddon::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}