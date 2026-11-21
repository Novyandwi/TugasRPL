<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'nomor_antrean', 'nama_pelanggan', 'total',
        'uang_dibayar', 'kembalian', 'status', 'kasir_id'
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    public function getTotalFormatted(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
