<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Host extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'userid',
        'eventdate',
        'closelist',
        'shownames',
        'userrevealid',
        'share_token',
    ];

    protected $casts = [
        'eventdate' => 'date:d/m/Y',
        'closelist' => 'date:d/m/Y',
        'shownames' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            if (empty($model->share_token)) {
                do {
                    $model->share_token = Str::random(40);
                } while (Host::where('share_token', $model->share_token)->exists());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
    public function revealedBy()
    {
        return $this->belongsTo(User::class, 'userrevealid', 'id');
    }
    public function hostNames()
    {
        return $this->hasMany(HostName::class, 'hostid', 'id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'hostid', 'id');
    }
    public function guests()
    {
        return $this->belongsToMany(User::class, 'hostuserguests', 'hostid', 'userid')->withTimestamps();
    }
}
