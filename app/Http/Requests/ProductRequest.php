<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Utilities\Functions\Functions;

class ProductRequest extends FormRequest
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
            //
            'section' => 'required|max:255|string|min:3',
            'category' => 'required|max:255|string|min:3',
            'image' => 'image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => 'required|max:255|string|min:3',
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required|max:255|string|min:3', 
                'regex:'. Functions::getURLRegexStr(),
            ],
            'price' => 'required|numeric',
            'sale' => 'numeric|nullable',
            'sticker' => 'string|regex:/new|sale/',
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
            'title.required' => 'A title is required',
            'body.required'  => 'A message is required',
        ];
    }
}
