<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photography extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'filename',
        'description',
        'user_id',
        'event_id',
    ];

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function user()
    {
        return $this->belongsTo('App/User');
    }

    public function event()
    {
        return $this->belongsTo('App/Event');
    }
}
