<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'inapp_android_package' => 'required_if:inapp_package_id',
            'inapp_package_id' => 'required_if:inapp_android_package',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'package_id' => 'required|numeric',
        ];
    }
}
