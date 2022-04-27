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

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
