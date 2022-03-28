<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GlobalMetafieldRequest extends FormRequest
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
            case 'global-metafield':
            {
                if (!$isSubmit) {
                    $id = $data['data']['id'];

                    $resourceType = [$data['resourceType'] ,'lcl_' . $data['resourceType'], 'sync_' . $data['resourceType'], 'global_' . $data['resourceType']];
                    $data = $data['data'];
                        $rules['data.namespace'] = 'required|min:3|max:20';

                    $rules['data.key'] = [
                        "required",
                        'min:3',
                        'max:30',
                        Rule::unique('metafield_configurations','key')->ignore($id)->where(function ($query) use($resourceType, $shop_id){
                            $query->whereIn('resource_type', $resourceType);
                            $query->where('shop_id', $shop_id);
                        }),
                    ];

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
                }
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
                $rules['data.namespace.required'] = 'required';
                $rules['data.namespace.min'] = 'Namespace must be atleast 3 character long';
                $rules['data.namespace.max'] = 'Namespace contains maximum 20 character';
                $rules['data.key.required'] = 'required';
                $rules['data.key.min'] = 'Key must be atleast 3 character long';
                $rules['data.key.max'] = 'Key contains maximum 30 character';
                $rules['data.key.unique'] = 'Key already exist';
                $rules['data.value.required'] = 'required';
                switch( $data['typev'] ){
                    case 'email':
                        $rules['data.value.email'] = 'Enter valid email address';
                        break;
                    case 'url':
                        $rules['data.value.url'] = 'Enter valid URL';
                        break;
                    case 'json':
                        $rules['data.value.json'] = "Enter valid JSON data";
                        break;
                    case 'phone':
                        $rules['data.value.phone'] = "Enter valid Phone number";
                        break;
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
