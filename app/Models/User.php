<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['nama_lengkap', 'username', 'password', 'role'];

    protected $hidden = ['password'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'kasir_id');
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin'   => '⚙️ Admin',
            'kasir'   => '🖥️ Kasir',
            'kitchen' => '👨‍🍳 Kitchen',
            default   => $this->role,
        };
    }
}
