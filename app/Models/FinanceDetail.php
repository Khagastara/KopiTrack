<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceDetail extends Model
{
    protected $table = 'finance_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'expenditure_cost',
        'expenditure_description',
        'id_finance'
    ];

    public function Finance()
    {
        return $this->belongsTo(Finance::class, 'id_finance', 'id');
    }
}
