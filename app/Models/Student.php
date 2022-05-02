<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ad_no', 'dob', 'class_id', 'batch_id', 'parent_id'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    protected $appends = [
        'parent_password'
    ];

    public function parent()
    {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }

    public function getparentPasswordAttribute()
    {
        return $this->dob->format('d.m.Y');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function class()
    {
        return $this->hasOne(Classes::class, 'id', 'class_id');
    }
}
