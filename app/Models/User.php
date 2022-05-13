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
        'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'balance',
    ];

    public function expenses()
    {
        return $this->hasMany(Transaction::class, 'reciever_id')->with('reciever');
    }

    public function deposits()
    {
        return $this->hasMany(Transaction::class, 'sender_id')->with('sender');
    }

    public function transactions()
    {
        return collect($this->deposits()->limit(25)->get())->merge($this->expenses()->limit(25)->get())->sortByDesc('created_at')->values();
    }

    public function getTotalIncomeAttribute()
    {
        return $this->deposits()->sum('amount');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return ($this->old_balance + $this->total_income) - $this->total_expenses - ($this->user_type == 'admin' ? User::where('id', '<>', $this->id)->sum('old_balance') : 0) ;
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'id', 'teacher_id');
        // return ($this->user_type == 'teacher' || $this->user_type == 'admin') ? $this->belongsTo(Classes::class, 'id', 'teacher_id') : $this->belongsTo(Classes::class, 'id', 'teacher_id');
    }

    public function meta()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
