<?php

namespace App\Models;

use App\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'pass',
        'typeuser',
        'firstaccess',
    ];

    protected $hidden = [
        'pass',
        'remember_token'
    ];

    protected $casts = [
        'typeuser' => UserType::class,
        'firstaccess' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setPassAttribute($value)
    {
        $this->attributes['pass'] = bcrypt($value);
    }

    public function getAuthPassword()
    {
        return $this->pass;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function hosts()
    {
        return $this->hasMany(Host::class, 'userid', 'id');
    }

    public function host()
    {
        return $this->hasOne(Host::class, 'userid');
    }

    public function revealedHosts()
    {
        return $this->hasMany(Host::class, 'userrevealid', 'id');
    }

    public function participatedHosts()
    {
        return $this->belongsToMany(Host::class, 'hostuserguests', 'userid', 'hostid');
    }
}
