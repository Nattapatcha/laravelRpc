<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Pesticide.
 *
 * @package namespace App\Entities;
 */
class Pesticide extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'group_id',
        'trademark_name',
        'bioactive_percent',
        'unit',
        'water_ratio',
        'used_for',
        'crop_day_length',
        'cide_group',
        'plant'
    ];

    public function group()
    {
        return $this->belongsTo(\App\Entities\Group::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'model_id')
            ->where('model', 'Pesticide')
            ->orderBy('id');
    }
}
