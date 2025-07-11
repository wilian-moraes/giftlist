<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'hostid',
        'productimg',
        'name',
        'link',
    ];

    protected $casts = [
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
        });

        static::deleting(function ($model) {
            $model->chooseproducts()->each(function ($product) {
                $product->guestnames()->delete();

                $product->delete();
            });
        });
    }

    public function host()
    {
        return $this->belongsTo(Host::class, 'hostid', 'id');
    }
    public function chooseProducts()
    {
        return $this->hasMany(ChooseProduct::class, 'productid', 'id');
    }
}
