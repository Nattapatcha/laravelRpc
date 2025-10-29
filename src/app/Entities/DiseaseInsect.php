<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class DiseaseInsect.
 *
 * @package namespace App\Entities;
 */
class DiseaseInsect extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'plant_disease_insects';

    protected $appends = ['type_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'cause',
        'symptom',
        'life_cycle',
        'effect',
        'protect_eliminate',
        'plant_type'
    ];

    public function media()
    {
        return $this->hasMany(Media::class, 'model_id')
            ->where('model', 'DiseaseInsect')
            ->orderBy('id');
    }

    public function getTypeNameAttribute()
    {
        $types = ['1' => 'โรค', '2' => 'แมลง'];
        return $types[$this->attributes['type']];
    }
}
