<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DiseaseInsectCreateRequest extends Request
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
            'name' => 'required',
            'cause' => 'required_if:type,1',
            'symptom' => 'required_if:type,1',
            'life_cycle' => 'required_if:type,2',
            'effect' => 'required_if:type,2',
            'protect_eliminate' => 'required',
            'plant_type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณากรอกชื่อ',
            'cause.required_if' => 'กรุณากรอกเชื้อสาเหตุ',
            'symptom.required_if' => 'กรุณากรอกลักษณะอาการ',
            'life_cycle.required_if' => 'กรุณากรอกวงจรชีวิต',
            'effect.required_if' => 'กรุณากรอกการทำลาย',
            'protect_eliminate.required' => 'กรุณากรอกการป้องกันกำจัด',
            'plant_type.required' => 'กรุณากรอกข้อมูลพืช',
            'image_1.mimes' => 'รูปที่ 1 รูปแบบไฟล์ไม่ถูกต้อง กรุณาอัพโหลดไฟล์ jpg, jpeg หรือ png',
            'image_2.mimes' => 'รูปที่ 2 รูปแบบไฟล์ไม่ถูกต้อง กรุณาอัพโหลดไฟล์ jpg, jpeg หรือ png',
            'image_3.mimes' => 'รูปที่ 3 รูปแบบไฟล์ไม่ถูกต้อง กรุณาอัพโหลดไฟล์ jpg, jpeg หรือ png',
            'image_4.mimes' => 'รูปที่ 4 รูปแบบไฟล์ไม่ถูกต้อง กรุณาอัพโหลดไฟล์ jpg, jpeg หรือ png',
            'image_5.mimes' => 'รูปที่ 5 รูปแบบไฟล์ไม่ถูกต้อง กรุณาอัพโหลดไฟล์ jpg, jpeg หรือ png',
        ];
    }
}
