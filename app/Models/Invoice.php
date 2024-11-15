<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'client_number',
        'client_address',
        'grand_total',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
