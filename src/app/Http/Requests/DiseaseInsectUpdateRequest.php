<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DiseaseInsectUpdateRequest extends DiseaseInsectCreateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
