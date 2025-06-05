<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory;

    protected $table = 'accounts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'username',
        'password',
        'email',
    ];

    protected $hidden = [
        'password',
    ];

    public function Admin() {
        return $this->hasOne(Admin::class, 'account_id', 'id');
    }

    public function Merchant() {
        return $this->hasMany(Merchant::class, 'account_id', 'id');
    }
}
