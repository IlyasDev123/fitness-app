<?php

namespace App\Http\Requests\Insight;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
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
        return [
            "title" => 'required',
            "description" => "required",
            "short_description" => "required",
            "category_id" => "required",
            "thumbnail" => "required",
            "duration" => "required|date_format:H:i:s"
        ];
    }

    public function messages()
    {
        return [
            "duration.date_format" => "The duration should time and follow the format hh:mm:ss."
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            sendErrorResponse(
                $validator->errors()->all()[0],
                422,
            ),
        );
    }
}
