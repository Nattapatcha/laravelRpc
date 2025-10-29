<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IssueCreateRequest extends Request
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
            "first_name" => 'required',
            "last_name" => 'required',
            "agriculturist_code" => 'required',
            "station" => 'required',
            "plant_type" => 'required',
            "disease_insects" => 'required',
            "area_percentage" => 'required'
        ];
    }

    public function messages()
    {
        return [
            "first_name.required" => 'กรุณากรอกชื่อ',
            "last_name.required" => 'กรุณากรอกนามสกุล',
            "agriculturist_code.required" => 'กรุณากรอกรหัสเกษตรกร',
            "station.required" => 'กรุณากรอกสถานี',
            "plant_type.required" => 'กรุณากรอกชนิดพืช',
            "disease_insects.required" => 'กรุณากรอกชนิดศัตรู',
            "area_percentage.required" => 'กรุณากรอก % ศัตรูพืช/พื้นที่ทั้งหมด'
        ];
    }
}
