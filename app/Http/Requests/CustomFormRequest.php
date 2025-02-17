<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomFormRequest extends FormRequest
{


    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        foreach ($errors as $error) {
            flash()->error($error);
        }

        throw new HttpResponseException(
            redirect()->back()->withInput()->withErrors($validator)
        );
    }
}
