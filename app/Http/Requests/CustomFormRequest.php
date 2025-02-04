<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomFormRequest extends FormRequest
{


    protected function failedValidation(Validator $validator)
{
    // Get all validation errors
    $errors = $validator->errors()->all();

    // Flash each error message separately
    foreach ($errors as $error) {
        flash()->error($error);
    }

    // Redirect back with input and validation errors
    throw new HttpResponseException(
        redirect()->back()->withInput()->withErrors($validator)
    );
}
}
