<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HabitUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $habit = $this->route('habit');
        return $this->user()->can('update', $habit);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $rules = [
            'title' => 'required|string|max:255',
            'emoji' => 'required|string|max:10',
            'levels' => 'required|array',
            'levels.*' => 'array|max:3',
            'levels.*.*.content' => 'required|string|max:255',
            'removedHabitLevelIds' => 'present|array',
        ];
    }
}
