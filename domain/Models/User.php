<?php

namespace Turno\Models;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract {

    use HasApiTokens, HasFactory, Notifiable, Authenticatable, Authorizable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'balance',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = (int) round($value * 100);
    }

    public function getBalanceAttribute($value){
        return (float)number_format($value / 100, 2, '.', '');
    }
}
