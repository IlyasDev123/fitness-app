<?php

namespace App\Http\Requests\Insight;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UpdateRequest extends FormRequest
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
            "insight_id" => "required",
            "title" => "required|string",
            "short_description" => "required|string",
            "description" => "required|string",
            "category_id" => "required|exists:categories,id",
            "thumbnail" => "nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048",
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
            sendErrorResponse( // phpcs:ignore
                $validator->errors()->all()[0],
                422,
            ),
        );
    }
}
