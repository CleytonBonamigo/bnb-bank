<?php

namespace Turno\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCheck extends Model {

    protected $fillable = ['transaction_id', 'url'];
}
