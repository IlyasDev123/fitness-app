<?php

namespace App\Http\Requests\Workout;

use Illuminate\Foundation\Http\FormRequest;

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
            "title" => "required|string",
            "description" => "required|string",
            "category_id" => "required|exists:categories,id",
            "thumbnail" => "nullable|file|mimes:jpeg,png,jpg,gif,svg|max:50000",
            "url" => "required|string",
            "is_featured" => "required|string",
            "duration" => "required|string",
            "status" => "required",
        ];
    }
}
