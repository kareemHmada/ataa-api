<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'title',
        'date',
        'img',
        'status',
        'description',
        'category',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
