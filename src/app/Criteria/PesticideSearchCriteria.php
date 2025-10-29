<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Repositories\GroupRepositoryEloquent;

class PesticideSearchCriteria implements CriteriaInterface
{
    protected $groupRepo;

    public function __construct(GroupRepositoryEloquent $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * Apply criteria in query repository
     *
     * @param mixed               $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $keyword = request()->query('keyword');
        if ($keyword) {
            $keyword = trim($keyword);
            $model = $model->where('name', 'like', "%{$keyword}%")
                ->orWhere('trademark_name', 'like', "%{$keyword}%")
                ->orWhere('plant', 'like', "%{$keyword}%")
                ->orWhere('used_for', 'like', "%{$keyword}%");

            // --- FIX IS HERE ---
            // Use a nested array for conditions with operators
            $groups = $this->groupRepo->findWhere([
                ['name', 'like', "%{$keyword}%"]
            ])->pluck('id')->all();
            
            if (!empty($groups)) {
                $model->orWhereIn('group_id', $groups);
            }
        }

        return $model;
    }
}