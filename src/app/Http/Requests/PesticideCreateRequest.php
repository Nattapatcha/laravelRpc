<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PesticideCreateRequest extends Request
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
            'name'               => 'required|string|max:255',
            'trademark_name'     => 'required|string|max:255',
            'bioactive_percent'  => 'required|string|max:100',
            'unit'               => 'required|string|max:50',
            'water_ratio'        => 'required|string|max:100',
            'used_for'           => 'required|string',
            'crop_day_length'    => 'required|integer|min:0|max:365',
            'cide_group'         => 'required|string|max:255',
            'plant'              => 'required|string',

            // รูปภาพ: อนุญาต jpg, jpeg, png, webp ขนาดไม่เกิน 4MB
            'image_1'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_2'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_3'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_4'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_5'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'name.required'               => 'กรุณากรอกชื่อสามัญ',
            'trademark_name.required'     => 'กรุณากรอกชื่อการค้า',
            'bioactive_percent.required'  => 'กรุณากรอกสารออกฤทธิ์ (%)',
            'unit.required'               => 'กรุณากรอกรูปแบบ',
            'water_ratio.required'        => 'กรุณากรอกอัตรา (ต่อน้ำ 20 ลิตร)',
            'cide_group.required'         => 'กรุณากรอกกลุ่มสาร',
            'crop_day_length.required'    => 'กรุณากรอกเว้นระยะก่อนเก็บเกี่ยว (วัน)',
            'crop_day_length.integer'     => 'กรุณากรอกเว้นระยะก่อนเก็บเกี่ยว (วัน) เป็นจำนวนเต็ม',
            'crop_day_length.min'         => 'เว้นระยะก่อนเก็บเกี่ยว (วัน) ต้องไม่น้อยกว่า :min',
            'crop_day_length.max'         => 'เว้นระยะก่อนเก็บเกี่ยว (วัน) ต้องไม่เกิน :max',
            'plant.required'              => 'กรุณากรอกชนิดพืช',
            'used_for.required'           => 'กรุณากรอกใช้ในการป้องกันและกำจัด',

            'image_1.image'               => 'รูปที่ 1 ต้องเป็นไฟล์รูปภาพ',
            'image_2.image'               => 'รูปที่ 2 ต้องเป็นไฟล์รูปภาพ',
            'image_3.image'               => 'รูปที่ 3 ต้องเป็นไฟล์รูปภาพ',
            'image_4.image'               => 'รูปที่ 4 ต้องเป็นไฟล์รูปภาพ',
            'image_5.image'               => 'รูปที่ 5 ต้องเป็นไฟล์รูปภาพ',

            'image_1.mimes'               => 'รูปที่ 1 รองรับเฉพาะไฟล์ jpg, jpeg, png หรือ webp',
            'image_2.mimes'               => 'รูปที่ 2 รองรับเฉพาะไฟล์ jpg, jpeg, png หรือ webp',
            'image_3.mimes'               => 'รูปที่ 3 รองรับเฉพาะไฟล์ jpg, jpeg, png หรือ webp',
            'image_4.mimes'               => 'รูปที่ 4 รองรับเฉพาะไฟล์ jpg, jpeg, png หรือ webp',
            'image_5.mimes'               => 'รูปที่ 5 รองรับเฉพาะไฟล์ jpg, jpeg, png หรือ webp',

            'image_1.max'                 => 'รูปที่ 1 ต้องมีขนาดไม่เกิน 4MB',
            'image_2.max'                 => 'รูปที่ 2 ต้องมีขนาดไม่เกิน 4MB',
            'image_3.max'                 => 'รูปที่ 3 ต้องมีขนาดไม่เกิน 4MB',
            'image_4.max'                 => 'รูปที่ 4 ต้องมีขนาดไม่เกิน 4MB',
            'image_5.max'                 => 'รูปที่ 5 ต้องมีขนาดไม่เกิน 4MB',
        ];
    }
}
