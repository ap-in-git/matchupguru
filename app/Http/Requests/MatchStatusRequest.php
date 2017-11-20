<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchStatusRequest extends FormRequest
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
    {   $rules=[
        "connection"=>"in:0,1,2"

    ];
    $seasons=$this->request->get("seasons");
        foreach ($seasons as $key=>$value){
            $rules['seasons.'.$key]='numeric';
        }
 $events=$this->request->get("events");
        foreach ($events as $key=>$value){
            $rules['events.'.$key]='numeric';
        }


        return $rules;


    }
}
