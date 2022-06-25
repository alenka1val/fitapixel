<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'photo_id',
        'event_id',
        'value'
    ];

    public function user()
    {
        return $this->belongsTo('App/User');
    }

    public function event()
    {
        return $this->belongsTo('App/Event');
    }

    public function photo()
    {
        return $this->belongsTo('App/Photography');
    }
}
