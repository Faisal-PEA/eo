<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Accessor pintar: jika gambar dari internet tetap pakai link aslinya, jika diupload dari lokal maka otomatis arahkan ke folder public/uploads/paket/
    public function getGambarAttribute($value)
    {
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset('uploads/paket/' . $value);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}