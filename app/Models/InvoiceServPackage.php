<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceServPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'service_pkg_id',
        'vehi_type_id',
        'cost',
        'discount',
        'tot_discount',
        'price',
        'sub_total',
    ];
}
