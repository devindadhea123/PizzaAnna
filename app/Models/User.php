<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getAuthIdentifierName()
{
    return 'id_user';
}

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_kasir', 'id_user');
    }
}