<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'max_volunteers',
        'image',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'event_date' => 'date',
    ];

    /**
     * Relationship: Event belongs to creator (User)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: Many volunteers (users) can join event
     * Many-to-Many relationship with User model
     */
    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'event_user')
                    ->withPivot('joined_at', 'status')
                    ->withTimestamps();
    }

    /**
     * Get approved volunteers count
     */
    public function getVolunteersCountAttribute()
    {
        return $this->volunteers()->wherePivot('status', 'approved')->count();
    }

    /**
     * Check if event is full
     */
    public function isFull()
    {
        return $this->volunteers()->wherePivot('status', 'approved')->count() >= $this->max_volunteers;
    }

    /**
     * Check if user has joined this event
     */
    public function hasUserJoined($userId)
    {
        return $this->volunteers()
                    ->where('user_id', $userId)
                    ->wherePivot('status', 'approved')
                    ->exists();
    }

    /**
     * Get available spots
     */
    public function availableSpots()
    {
        $currentVolunteers = $this->volunteers()->wherePivot('status', 'approved')->count();
        return max(0, $this->max_volunteers - $currentVolunteers);
    }
}