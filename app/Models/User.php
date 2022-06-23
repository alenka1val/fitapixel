<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'web',
        'address_street',
        'address_city',
        'address_zip_code',
        'password',
        'school',
        'year_school_termination',
        'year_school_termination_stu',
        'specialisation',
        'education_attainment_stu',
        'ais_uid',
        'group_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin()
    {
        return $this['is_admin'];
    }

    public function photographies()
    {
        return $this->hasMany('App\Photographies');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function group()
    {
        return $this->belongsTo('App/Group');
    }
}
