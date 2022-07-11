<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reciever_id', 'sender_id', 'amount', 'description', 'category_id', 'remarks', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'category'
    ];

    public function getCalculatedAmount()
    {
        $value = $this->amount - $this->remarks ?? 0;
        return round($value, 2);
    }

    public function getAmountAttribute($value)
    {
        return round($value, 2);
    }

    public function getCategoryAttribute()
    {
        return $this->belongsTo(Category::class, 'category_id')->value('name');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reciever()
    {
        return $this->belongsTo(User::class, 'reciever_id');
    }
}
