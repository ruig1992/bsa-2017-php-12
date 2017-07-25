<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreCarRental
 * @package App\Http\Requests
 */
class StoreCarRental extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'rented_from' => 'bail|required|integer|exists:locations,id',
            'returned_to' => 'bail|integer|exists:locations,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'rented_from.required' => 'This location must be selected.',
            '*.integer' => 'The selected location has an invalid type.',
            '*.exists' => 'The selected location does not exist.',
        ];
    }
}
