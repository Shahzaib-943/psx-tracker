<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends CustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        return [
            'name' => 'required | min:3 | max:255',
            'email' => ['required',  Rule::unique('users')->ignore($user), 'min:3' , 'max:255'],
            'password' => 'sometimes | nullable | confirmed | min:8 | max:255',
            'role_id' => 'required | string | max:155 | exists:roles,name'
        ];
    }
}
