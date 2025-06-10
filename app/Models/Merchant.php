<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchant extends Authenticatable
{
    use HasFactory;

    protected $table = 'merchants';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'merchant_name',
        'phone_number',
        'id_account',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_merchant', 'id');
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
