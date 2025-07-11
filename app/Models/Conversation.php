<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        
        'receiver_id',   
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

  
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
