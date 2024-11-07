<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => Response::HTTP_BAD_REQUEST,
            'status'   => 'error',
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
