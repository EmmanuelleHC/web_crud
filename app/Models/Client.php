<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['client_number', 'client_name', 'client_address'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
