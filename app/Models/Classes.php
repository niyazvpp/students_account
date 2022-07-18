<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'name', 'fullname'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id')->where('user_type', 'teacher');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
