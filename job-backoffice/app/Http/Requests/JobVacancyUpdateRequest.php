<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobVacancyUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title'           => 'bail|required|string|max:255',
            'description'     => 'bail|required|string|min:50',
            'required_skills' => 'bail|required|string',
            'location'        => 'bail|required|string|max:255',
            'salary'          => 'bail|required|numeric|min:0',
            'type'            => 'bail|required|in:full-time,hybrid,contract,remote',

            'company_id'      => 'bail|required|uuid|exists:companies,id',

            'category_ids'    => 'bail|required|array|min:1',
            'category_ids.*'  => 'bail|uuid|exists:categories,id',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required'          => 'The job title is required.',
            'description.required'    => 'Please provide a detailed job description.',
            'description.min'         => 'The description should be at least 50 characters.',
            'location.required'       => 'Location is required.',
            'type.required'           => 'Please select an employment type.',
            'type.in'                 => 'The selected employment type is invalid.',
            'company_id.required'     => 'Please select a company.',
            'company_id.exists'       => 'The selected company does not exist.',
            'category_ids.min'        => 'Please select at least one category.',
            'category_ids.*.exists'   => 'One or more selected categories are invalid.',
        ];
    }

    protected function prepareForValidation()
    {
        if (auth()->user() && auth()->user()->role === 'company-owner') {
            $company = auth()->user()->companies()->first();
            $this->merge([
                'company_id' => $company?->id,
            ]);
        }
    }
}
