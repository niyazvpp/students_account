<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(Transaction::class, 'reciever_id')->with('sender');
    }

    public function deposits()
    {
        return $this->hasMany(Transaction::class, 'sender_id')->with('reciever');
    }

    public function transactions()
    {
        return Transaction::where('sender_id', $this->id)->orWhere('reciever_id', $this->id)->with('sender', 'reciever')->latest();
    }

    public function getTotalIncomeAttribute()
    {
        $number = $this->deposits()->sum(DB::raw('if(remarks is not null, amount - remarks, amount)')) ?? 0;
        return round($number, 2);
    }

    public function getTotalExpensesAttribute()
    {
        $number = $this->expenses()->sum(DB::raw('if(remarks is not null, amount - remarks, amount)')) ?? 0;
        return round($number, 2);
    }

    public function getBalanceAttribute()
    {
        $number = ($this->old_balance + $this->total_income) - $this->total_expenses;
        return round($number, 2);
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

    public function is($type)
    {
        return $this->user_type == $type;
    }

    public function isAdmin()
    {
        return $this->is('admin');
    }
}
