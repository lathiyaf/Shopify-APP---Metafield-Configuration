<?php

namespace App\Http\Requests;

use App\Rules\ValidMetafields;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class MetafieldConfigurationRequest extends FormRequest
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
        $shop_id = \ShopifyApp::shop()->id;

        switch (Route::currentRouteName()) {
            case 'metafieldconfiguration.store':
            {
                $resourceType = $data['resourceType'];
                $data = $data['data'];

                foreach( $data as $k=>$v ){
                    $id = $data[$k]['id'];
                    $rules['data.' . $k . '.namespace'] = 'required|min:3|max:20';
//                    $rules['data.' . $k . '.key'] = [
//                        'required','min:3',
//                        Rule::unique('metafield_configurations','key')->ignore($data[$k]['id'])->where(function ($query) use ($resourceType, $shop_id){
//                            $query->where('resource_type', $resourceType);
//                            $query->where('shop_id', $shop_id);
//                        })
//                    ];
                    $rules['data.' . $k . '.key'] = "required|min:3|max:30|unique:metafield_configurations,key,{$id},id,resource_type,{$resourceType},shop_id,{$shop_id}";
//                    $rules['data.' . $k . '.label'] = 'required';
                }
                return $rules;
            }
            case 'metafieldconfiguration.update':
            {
                return $rules;
            }

            case 'allmetafieldconfiguration':
            {
                $data = $data['data'];
                foreach( $data as $key=>$val ){
                   if( $val ){
                       foreach( $val as $k=>$v ) {
                           $id = $data[$key][$k]['id'];
                           $resourceType = $data[$key][$k]['rtype'];

                           $rules['data.' . $key . '.' . $k . '.namespace'] = 'required|min:3|max:20';
                           $rules['data.' . $key . '.' .  $k  . '.key'] = "required|min:3|max:30|unique:metafield_configurations,key,{$id},id,resource_type,{$resourceType},shop_id,{$shop_id}";
//                           $rules['data.' . $key . '.' . $k . '.label'] = 'required';
                       }
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
        $data = $data['data'];
        foreach( $data as $k=>$v ){
            $rules['data.' . $k . '.namespace.required'] = 'required';
            $rules['data.' . $k . '.namespace.min'] = 'Namespace must be atleast 3 character long';
            $rules['data.' . $k . '.namespace.max'] = 'Namespace contains maximum 20 character';
            $rules['data.' . $k . '.key.required'] = 'required';
            $rules['data.' . $k . '.key.min'] = 'Key must be atleast 3 character long';
            $rules['data.' . $k . '.key.max'] = 'Key contains maximum 30 character';
            $rules['data.' . $k . '.key.unique'] = 'Key already exist';
//            $rules['data.' . $k . '.label.*'] = 'required';
        }

        foreach( $data as $key=>$val ){
            if( $val ) {
                foreach ($val as $k => $v) {
                    $rules['data.' . $key . '.' . $k . '.namespace.required'] = 'required';
                    $rules['data.' . $key . '.' . $k . '.namespace.min'] = 'Namespace must be atleast 3 character long';
                    $rules['data.' . $key . '.' . $k . '.namespace.max'] = 'Namespace contains maximum 20 character';
                    $rules['data.' . $key . '.' . $k . '.key.required'] = 'required';
                    $rules['data.' . $key . '.' . $k . '.key.min'] = 'Key must be atleast 3 character long';
                    $rules['data.' . $key . '.' . $k . '.key.max'] = 'Key contains maximum 30 character';
                    $rules['data.' . $key . '.' . $k . '.key.unique'] = 'Key already exist';
//                    $rules['data.' . $key . '.' . $k . '.label.*'] = 'required';
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
