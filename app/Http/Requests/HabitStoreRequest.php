<?php

namespace App\Http\Requests;

use App\Models\Log;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Concerns\ValidatesHabitLimit;

class HabitStoreRequest extends FormRequest
{
    use ValidatesHabitLimit;
    
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
        // 공통 검사 사항
        return $rules = [
            'title' => 'required|string|max:255',
            'emoji' => 'required|string|max:10',
            'levels' => 'required|array',
            'levels.*' => 'array|max:3',
            'levels.*.*.content' => 'required|string|max:255',
        ];
    }
}
