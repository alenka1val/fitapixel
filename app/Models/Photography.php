<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photography extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'filename', 'description', 'theme', 'event_id',
    ];

    public function user()
    {
        return $this->belongsTo('App/User');
    }

    public function event()
    {
        return $this->belongsTo('App/Event');
    }
}
