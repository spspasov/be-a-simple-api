<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'begin', 'end', 'title', 'candidate_limit'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Return all the users that have signed up for this course.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidates()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * Return the number of candidates that have signed up for the course.
     *
     * @return mixed
     */
    public function numberOfCandidates()
    {
        return $this->candidates()->count();
    }

    /**
     * Returns whether or not the candidate limit has been reached.
     *
     * @return bool
     */
    public function candidateLimitReached()
    {
        return $this->numberOfCandidates() >= $this->candidate_limit;
    }
}
