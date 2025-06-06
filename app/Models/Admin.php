<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function Account() {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }

    public function DistributionProduct()
    {
        return $this->hasMany(DistributionProduct::class, 'id_admin', 'id');
    }
}
