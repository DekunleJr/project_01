<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contributionGroup extends Model
{
    /** @use HasFactory<\Database\Factories\ContributionGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'users',
        'start_date',
        'end_date',
    ];

    // Cast JSON users field to array automatically
    protected $casts = [
        'users' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'users', 'id');
    }
}
