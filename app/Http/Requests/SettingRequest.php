<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class SettingRequest extends FormRequest
{
    public static $rules = [];
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
        $rules = Self::$rules;
        $data = $this::all();
        switch (Route::currentRouteName()) {
            case 'setting.store':
            {
                $rules['data.global_namespace'] = 'nullable|min:3|max:20';
                $rules['data.email'] = 'nullable|email';
                return $rules;
            }
            case 'setting.update':
            {
                return $rules;
            }

            default:
                break;
        }
    }


    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $rules = [];
        $rules['data.global_namespace.min'] = 'Key must be atleast 3 character long';
        $rules['data.global_namespace.max'] = 'Key must be maximum 20 character long';
        $rules['data.email.*'] = 'Enter valid email address';

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->ajax() || $this->wantsJson()) {
            $response = new JsonResponse($validator->errors(), 422);
            throw new ValidationException($validator, $response);
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag);
    }
}
