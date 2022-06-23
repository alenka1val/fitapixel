<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'url_path',
        'started_at',
        'finished_at',
        'voted_at',
        'voted_to',
        'image_folder',
        'min_width',
        'max_width',
        'min_height',
        'max_height',
        'allowed_ratios',
    ];

    public function photographies()
    {
        return $this->hasMany('App\Photography');
    }

    public function themes()
    {
        return $this->hasMany('App\ThemeEvent');
    }

    public function sponsors()
    {
        return $this->hasMany('App\SponsorEvent');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
}
