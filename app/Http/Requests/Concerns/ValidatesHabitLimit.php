<?php

namespace App\Http\Requests\Concerns;

use App\Models\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

trait ValidatesHabitLimit
{
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $currentLogsCount = Log::where('creator_id', $this->user()->id)
                ->whereDate('start_date', '<=', now())  
                ->whereDate('end_date', '>=', now())
                ->count();

            if ($currentLogsCount >= 3) {
                $validator->errors()->add('error', '3개 이상 습관을 동시에 진행할 수 없습니다.');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first('error');

        if ($message) {
            $response = redirect()
                ->back()
                ->with('error', $message);

            throw new ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }
}