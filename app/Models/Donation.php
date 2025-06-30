<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'title',
        'date',
        'img',
        'statua',
        'description',
        'category',
    ];
}
