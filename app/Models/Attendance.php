<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
 protected $fillable = [
        'user_id',
        'event_id'
    ];

    /**
     * Get the user associated with the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event associated with the attendance.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }}
