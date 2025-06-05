<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $table = 'finances';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'finance_date',
        'total_quantity',
        'income_balance',
        'expenditure_balance'
    ];

    public function Transaction()
    {
        return $this->hasMany(Transaction::class, 'id_finance', 'id');
    }
}
