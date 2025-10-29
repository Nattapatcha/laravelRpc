<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class DiseaseInsectSearchCriteria.
 *
 * @package namespace App\Criteria;
 */
class DiseaseInsectSearchCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @param RepositoryInterface $repository
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $keyword = request()->query('keyword');
        if (request()->query('type')) {
            $model = $model->where('type', '=', request()->query('type'));
        }

        if ($keyword) {
            $keyword = trim($keyword);
            $model = $model->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'like', "%{$keyword}%");
                $query->orWhere('cause', 'like', "%{$keyword}%");
                $query->orWhere('symptom', 'like', "%{$keyword}%");
                $query->orWhere('life_cycle', 'like', "%{$keyword}%");
                $query->orWhere('effect', 'like', "%{$keyword}%");
                $query->orWhere('protect_eliminate', 'like', "%{$keyword}%");
                $query->orWhere('plant_type', 'like', "%{$keyword}%");
            });
        }



        return $model;
    }
}
