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
        'due_date',
        'total_amount',
        'amount_paid',
        'status_id',
        'tva_id',
        'user_id',
    ];

    protected $hidden = [
        'user_id',
    ];

    public function tva()
    {
        return $this->belongsTo(Tva::class);
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }
}
