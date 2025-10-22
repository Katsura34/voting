<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:parties,name'
            ],
            'slogan' => [
                'required',
                'string',
                'max:255'
            ],
            'color' => [
                'required',
                'string',
                'regex:/^#([0-9a-f]{3}){1,2}$/i'
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB max
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A party with this name already exists.',
            'color.regex' => 'Please provide a valid hex color code.',
            'logo.max' => 'Logo file size must not exceed 2MB.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if maximum parties (2) already exist
            $partyCount = \App\Models\Party::count();
            if ($partyCount >= 2) {
                $validator->errors()->add('name', 'Maximum of 2 political parties allowed.');
            }
        });
    }
}