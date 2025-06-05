<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'quantity',
        'sub_price',
        'id_transaction',
        'id_distribution_product'
    ];

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction', 'id');
    }

    public function DistributionProduct()
    {
        return $this->belongsTo(DistributionProduct::class, 'id_distribution_product', 'id');
    }
}
