<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentMeta extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id' ];

    public function students()
    {
        return $this->hasMany(Student::class, 'user_id', 'id');
    }

}
