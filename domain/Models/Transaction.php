<?php

namespace Turno\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    use HasFactory;

    protected $fillable = ['status_id', 'type_id', 'customer_id', 'amount', 'description'];

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (int) round($value * 100);
    }

    public function getAmountAttribute($value)
    {
        return (float)number_format($value / 100, 2, '.', '');
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function status()
    {
        return $this->belongsTo(TransactionStatus::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function check()
    {
        return $this->hasOne(TransactionCheck::class);
    }
}
