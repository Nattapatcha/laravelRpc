<?php

namespace App\Http\Requests;

use App\Http\Requests\PesticideCreateRequest;

class PesticideUpdateRequest extends PesticideCreateRequest
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
     * กฎอัปเดต: ใช้กฎเดียวกับสร้าง + รองรับการส่ง delete_image[]
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        // รองรับลบรูปแบบเป็น array ของ id
        $rules['delete_image']   = 'sometimes|array';
        $rules['delete_image.*'] = 'integer';

        return $rules;
    }

    public function messages()
    {
        // รวมข้อความจาก create + ข้อความของ delete_image
        return array_merge(parent::messages(), [
            'delete_image.array'  => 'รูปที่ต้องการลบต้องอยู่ในรูปแบบรายการ (array)',
            'delete_image.*.integer' => 'ค่าที่ส่งมาสำหรับการลบรูปไม่ถูกต้อง',
        ]);
    }
}
