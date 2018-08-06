<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required|alpha|min:2|max:70',
            'lastname' => 'required|alpha|min:2|max:70',
            'email' => 'required|email',
            'password' => 'required|min:6|max:70|alpha_num|confirmed',
            'terms' => 'required|accepted',
            'privacy' => 'required|accepted',
            //'image' => 'nullable|file|image'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'The First Name field is Required!',
            'firstname.min' => 'First Name is Too Short',
            'firstname.max' => 'First Name is Too Long',
            'lastname.required' => 'The Last Name field is Required!',
            'lastname.min' => 'Last Name is Too Short',
            'lastname.max' => 'Last Name is Too Long',
            'email.required'  => 'An Email is required',
            'email.email' => 'A Valid Email Address is Required',
            'password.required' => 'The Password field is Required!',
            'password.min' => 'Your Password is Too Short',
            'password.max' => 'Your Password is Too Long',
            'terms.required' => 'Our Terms of Service MUST be Accepted!',
            'privacy.required' => 'Our Privacy Policy MUST be Accepted!'
        ];
    }
}
