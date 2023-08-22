<?php

namespace Turno\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model {

    protected $table    = 'transaction_status';
    protected $fillable = ['description'];
}
