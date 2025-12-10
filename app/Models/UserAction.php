<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userAction extends Model
{
    /** @use HasFactory<\Database\Factories\UserActionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_action',
        'user_id',
    ];
}
