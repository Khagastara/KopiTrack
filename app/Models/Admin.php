<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'admin_name',
        'phone_number',
        'id_account',
    ];

    public function account() {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }

    public function distributionProducts()
    {
        return $this->hasMany(DistributionProduct::class, 'id_admin', 'id');
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->account->password ?? null;
    }
}
