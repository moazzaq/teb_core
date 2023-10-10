<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
    public function rules()
    {
//        $categoryId = $this->route('category'); // Get the category ID from the route
//
//        return [
//            'name_ar' => 'required|unique:categories,name->ar,' . $categoryId,
//            'name_en' => 'required|unique:categories,name->en,' . $categoryId,
//            'country_id.*' => 'exists:countries,id',
//        ];

        $categoryId = $this->input('id');

        return [
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name->en')->ignore($categoryId),
            ],
            'name_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name->ar')->ignore($categoryId),
            ],
            // Add other validation rules for your form fields
        ];
    }

    public function messages()
    {
        return [
            'name_ar.required' => __('validation.required'),
            'name_en.required' => __('validation.required'),
            'name_ar.unique' => __('validation.unique'),
            'name_en.unique' => __('validation.unique'),
            'country_id.exists' => __('validation.exists'),
        ];
    }
}
