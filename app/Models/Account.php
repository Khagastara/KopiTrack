<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function admin() {
        return $this->hasOne(Admin::class, 'id_account', 'id');
    }

    public function merchant() {
        return $this->hasOne(Merchant::class, 'id_account', 'id');
    }

    public function isAdmin() {
        return $this->admin()->exists();
    }

    public function isMerchant() {
        return $this->merchant()->exists();
    }

    public function getRole() {
        if ($this->isAdmin()) {
            return 'admin';
        } elseif ($this->isMerchant()) {
            return 'merchant';
        }
        return null;
    }
}
