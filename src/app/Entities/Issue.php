<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Issue.
 *
 * @package namespace App\Entities;
 */
class Issue extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "agriculturist_code",
        "station",
        "plant_type",
        "disease_insects",
        "area_percentage",
        "lat",
        "long"
    ];
    protected $casts = [
        'area_percentage' => 'decimal:2',
    ];
    protected $appends = ['full_name'];

    public function media()
    {
        return $this->hasMany(\App\Entities\Media::class, 'model_id')
            ->where('model', class_basename(self::class));
    }

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
