<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['nama', 'kategori', 'harga', 'stok', 'emoji'];

    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    public function isHabis(): bool
    {
        return $this->stok <= 0;
    }

    public function getHargaFormatted(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
