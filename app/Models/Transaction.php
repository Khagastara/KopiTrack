<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'transaction_date',
        'id_merchant',
        'id_finance'
    ];

    protected $casts = [
        'transaction' => 'date'
    ];

    public function Merchant()
    {
        return $this->belongsTo(Merchant::class, 'id_merchant', 'id');
    }

    public function Finance()
    {
        return $this->belongsTo(Finance::class, 'id_finance', 'id');
    }

    public function TransactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id');
    }
}
