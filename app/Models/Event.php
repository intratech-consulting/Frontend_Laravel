<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
   use HasFactory;

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'location',
        'max_registrations',
        'available_seats',
        'description',
        'speaker_user_id',
        'speaker_company_id',
        // Add any other attributes here that are fillable
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'speaker_user_id');
    }

    public function companies()
    {
        return $this->belongsTo(Company::class, 'speaker_company_id');
    }
}
