<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class IssueSearchCriteria.
 *
 * @package namespace App\Criteria;
 */
class IssueSearchCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $keyword = request()->query('keyword');
        if ($keyword) {
            $keyword = trim($keyword);
            $model = $model->where('first_name', 'like', "%{$keyword}%")
                ->orWhere('last_name', 'like', "%{$keyword}%")
                ->orWhere('agriculturist_code', 'like', "%{$keyword}%");
        }

        return $model;
    }
}
