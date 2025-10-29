<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DiseaseInsectRepository;
use App\Entities\DiseaseInsect;
use App\Validators\DiseaseInsectValidator;

/**
 * Class DiseaseInsectRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class DiseaseInsectRepositoryEloquent extends BaseRepository implements DiseaseInsectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DiseaseInsect::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DiseaseInsectValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
