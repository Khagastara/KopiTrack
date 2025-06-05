<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionProduct extends Model
{
    protected $table = 'distribution_products';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'product_name',
        'product_image',
        'product_quantity',
        'product_price',
        'product_description',
        'id_admin',
    ];

    public function Admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }

    public function TransactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'id_distribution_product', 'id');
    }
}
