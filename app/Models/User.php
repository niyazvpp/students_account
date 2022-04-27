<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'user_type', 'mobile', 'old_balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function expenses()
    {
        return $this->hasMany(Transaction::class, 'reciever_id');
    }

    public function deposits()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    public function getTotalIncomeAttributes()
    {
        return $this->expenses()->sum('amount');
    }

    public function getTotalExpensesAttributes()
    {
        return $this->deposits()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->oldBalance +  $this->total_expenses;
    }
}
