<?php

namespace App\Http\Requests;

use App\Http\Resources\ValidationErrorResource;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['string', 'required'],
            'price' => ['numeric', 'between:0,999999.99', 'required'],
            'qty' => ['integer', 'between:0,999999', 'required'],
            'options' => ['array'],
            'options.*.title' => ['string', 'required_with:options'],
            'options.*.value' => ['string', 'required_with:options'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorResponse = new ValidationErrorResource([
            'errors' => $validator->errors(),
        ]);

        throw new ValidationException( $validator, $errorResponse);
    }
}
