<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreCarReturn
 * @package App\Http\Requests
 */
class StoreCarReturn extends FormRequest
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
            'returned_to' => 'bail|required|integer|exists:locations,id',
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
            'returned_to.required' => 'The location must be selected.',
            'returned_to.integer' => 'The selected location has an invalid type.',
            'returned_to.exists' => 'The selected location does not exist.',
        ];
    }
}
