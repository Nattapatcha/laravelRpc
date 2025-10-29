<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest
{
    /**
     * {@inheritdoc}
     */
    public function failedValidation(Validator $validator)
{
    if ($this->expectsJson()) {
        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }

    throw new HttpResponseException(
        redirect()->back()
            ->withInput()
            ->withErrors($validator)
    );
}

}
