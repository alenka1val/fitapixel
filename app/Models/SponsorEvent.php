<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'event_id',
        'sponsor_id',
    ];

    public function event()
    {
        return $this->belongsTo('App/Event');
    }

    public function sponsor()
    {
        return $this->belongsTo('App/Sponsor');
    }
}
