<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreElectionRequest extends FormRequest
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
                'unique:elections,name'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'start_date' => [
                'required',
                'date',
                'after:now'
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date'
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'An election with this name already exists.',
            'start_date.after' => 'Start date must be in the future.',
            'end_date.after' => 'End date must be after start date.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if there's already an active election
            $activeElection = \App\Models\Election::where('status', 'active')->first();
            if ($activeElection) {
                $validator->errors()->add('name', 'Another election is currently active. Close it before creating a new one.');
            }

            // Validate minimum duration (1 hour)
            if ($this->start_date && $this->end_date) {
                $start = Carbon::parse($this->start_date);
                $end = Carbon::parse($this->end_date);
                
                if ($end->diffInHours($start) < 1) {
                    $validator->errors()->add('end_date', 'Election must run for at least 1 hour.');
                }
            }
        });
    }
}