<?php

namespace App\Http\Requests\Vehicle;

use App\Enums\VehicleType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
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
            'year_of_manufacture' => 'required|integer',
            'color' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'type' => 'required|string|' . Rule::in(VehicleType::getValues()),
            'engine' => 'required|string',
            'passenger_capacity' => 'integer|required_if:type,' . VehicleType::Car->value,
            'car_type' => 'string|required_if:type,' . VehicleType::Car->value,
            'suspension_type' => 'string|required_if:type,' . VehicleType::Motorcycle->value,
            'transmission_type' => 'string|required_if:type,' . VehicleType::Motorcycle->value
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'   => 'error',
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400));
    }
}
