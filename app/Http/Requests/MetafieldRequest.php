<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class MetafieldRequest extends FormRequest
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
        $isSubmit = $data['isSubmit'];
        $shop_id = \ShopifyApp::shop()->id;

        switch (Route::currentRouteName()) {
            case 'metafield.store':
            {
                if (!$isSubmit) {
                    $type = @$data['type'];

                    $resourceType = 'lcl_' . $data['resourceType'];
                    $data = $data['data'];
                    if ($type == 'custom') {
                        $rules['data.namespace'] = 'required|min:3|max:20';
                        $rules['data.key'] = "required|min:3|max:30|unique:metafield_configurations,key,NULL,id,resource_type,{$resourceType},shop_id,{$shop_id}";
//                        $rules['data.label'] = 'required';
                        $rules['data.value'] = 'required';
                        switch( $data['typev'] ){
                            case 'email':
                                $rules['data.value'] = "required|email";
                                break;
                            case 'url':
                                $rules['data.value'] = "required|url";
                                break;
                            case 'json':
                                $rules['data.value'] = "required|json";
                                break;
                            case 'phone':
                                $rules['data.value'] = "required|regex:/[0-9-]/|min:10";
                                break;
                        }

                    } else {
                        foreach ($data as $k => $v) {
                            if ($v['type'] == 'email') {
                                $rules['data.' . $k . '.value'] = "nullable|email";
                            } elseif ($v['type'] == 'url') {
                                $rules['data.' . $k . '.value'] = "nullable|url";
                            } elseif ($v['type'] == 'json') {
                                $rules['data.' . $k . '.value'] = "nullable|json";
                            } elseif ($v['type'] == 'phone') {
                                $rules['data.' . $k . '.value'] = "nullable|regex:/[0-9-]/|min:10";
                            }
                        }
                    }
                }
                return $rules;
            }
            case 'metafield.update':
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
        $data = $this::all();
        $type = @$data['type'];
        $isSubmit = $data['isSubmit'];
        $data = $data['data'];

        if (!$isSubmit) {
            if ($type == 'custom') {
                $rules['data.namespace.required'] = 'required';
                $rules['data.namespace.min'] = 'Namespace must be atleast 3 character long';
                $rules['data.namespace.max'] = 'Namespace contains maximum 20 character';
                $rules['data.key.required'] = 'required';
                $rules['data.key.min'] = 'Key must be atleast 3 character long';
                $rules['data.key.max'] = 'Key contains maximum 30 character';
                $rules['data.key.unique'] = 'Key already exist';
//                $rules['data.label.*'] = 'required';
                $rules['data.value.*'] = 'required';
                switch( $data['typev'] ){
                    case 'email':
                        $rules['data.value.*'] = 'Enter valid email address';
                        break;
                    case 'url':
                        $rules['data.value.*'] = 'Enter valid URL';
                        break;
                    case 'json':
                        $rules['data.value.*'] = "Enter valid JSON data";
                        break;
                    case 'phone':
                        $rules['data.value.*'] = "Enter valid Phone number";
                        break;
                }
            } else {
                foreach ($data as $k => $v) {
                    if ($v['type'] == 'email') {
                        $rules['data.' . $k . '.value.*'] = 'Enter valid email address';
                    } else if ($v['type'] == 'url') {
                        $rules['data.' . $k . '.value.*'] = 'Enter valid URL';
                    } elseif ($v['type'] == 'json') {
                        $rules['data.' . $k . '.value.*'] = "Enter valid JSON data";
                    } elseif ($v['type'] == 'phone') {
                        $rules['data.' . $k . '.value.*'] = "Enter valid Phone number";
                    }
                }
            }
        }
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
