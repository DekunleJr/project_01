<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class contribution_payment extends Model
{
    //
    protected $fillable = [
        'contribution_group_id',
        'user_id',
        'amount',
        'had_paid',
        'due_date',
        'cycle',
    ];
}
