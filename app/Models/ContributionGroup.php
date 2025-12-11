<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class ContributionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'users',
        'start_date',
        'end_date',
        'frequency',
        'individualAmount',
        'amount',
    ];

    protected $casts = [
        'users' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Accessor - now "members" is an attribute, not a method
    public function getMembersAttribute()
    {
        $ids = $this->users;

        if (!is_array($ids)) {
            return collect();
        }

        return User::whereIn('id', $ids)->get();
    }
}
