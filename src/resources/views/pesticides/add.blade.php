@php
    $isEdit = isset($pesticide);
    $header = $isEdit ? 'แก้ไขข้อมูลสารกำจัดศัตรูพืช' : 'เพิ่มข้อมูลสารกำจัดศัตรูพืช';
    $url = $isEdit ? route('pesticides.update', ['id' => $pesticide->id]) : route('pesticides.store');
@endphp

@extends('layouts.app')
@section('page_header', $header)

@section('content')
    @include('layouts.flash_message')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="upload-image-app" class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $header }}</div>
                <div class="panel-body">
                    <form action="{{ $url }}" method="{{ $isEdit ? 'POST' : 'POST' }}" enctype="multipart/form-data">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="name">ชื่อสามัญ</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $pesticide->name ?? '') }}">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="trademark_name">ชื่อการค้า</label>
                                        <input type="text" name="trademark_name" id="trademark_name" class="form-control"
                                            value="{{ old('trademark_name', $pesticide->trademark_name ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="group_id">ประเภทสารกำจัดศัตรูพืช</label>
                                        <select name="group_id" id="group_id" class="form-control">
                                            @foreach($groups as $key => $label)
                                                <option value="{{ $key }}" {{ old('group_id', $pesticide->group_id ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="bioactive_percent">สารออกฤทธิ์ (%)</label>
                                        <input type="text" name="bioactive_percent" id="bioactive_percent"
                                            class="form-control"
                                            value="{{ old('bioactive_percent', $pesticide->bioactive_percent ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="unit">รูปแบบ</label>
                                        <input type="text" name="unit" id="unit" class="form-control"
                                            value="{{ old('unit', $pesticide->unit ?? '') }}">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="water_ratio">อัตรา (ต่อน้ำ 20 ลิตร)</label>
                                        <input type="text" name="water_ratio" id="water_ratio" class="form-control"
                                            value="{{ old('water_ratio', $pesticide->water_ratio ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="cide_group">กลุ่มสาร</label>
                                        <input type="text" name="cide_group" id="cide_group" class="form-control"
                                            value="{{ old('cide_group', $pesticide->cide_group ?? '') }}">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="crop_day_length">เว้นระยะก่อนเก็บเกี่ยว (วัน)</label>
                                        <input type="text" name="crop_day_length" id="crop_day_length" class="form-control"
                                            value="{{ old('crop_day_length', $pesticide->crop_day_length ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <label for="plant">ชนิดพืช</label>
                                        <input type="text" name="plant" id="plant" class="form-control"
                                            value="{{ old('plant', $pesticide->plant ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <label for="used_for">ใช้ในการป้องกันและกำจัด</label>
                                        <textarea name="used_for" id="used_for"
                                            class="form-control">{{ old('used_for', $pesticide->used_for ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label for="image">รูป</label>
                                <div class="flex-container">
                                    <div class="row">
                                        @php
                                            $mediaList = isset($pesticide) && $pesticide->media
                                                ? $pesticide->media->take(5)
                                                : collect();
                                        @endphp

                                        @foreach($mediaList as $media)
                                            <div class="col">
                                                <div class="img-upload-container">
                                                    <div data-id="{{ $media->id }}" class="delete-img-btn">X</div>
                                                    <div class="img-container">
                                                        <img class="img-thumb"
                                                            src="{{ $media->image_url ?? asset('images/no-image.svg') }}"
                                                            loading="lazy" width="240" height="160"
                                                            alt="{{ $pesticide->name ?? 'image' }}">
                                                    </div>
                                                    <div class="img-upload-file has-uploaded-img"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <label for="image-1" class="form-label">อัพโหลดไฟล์</label><br>
                                    <input type="file" name="image_1" id="image-1" class="img-file" accept="image/*">
                                    <input type="file" name="image_2" id="image-2" class="img-file" accept="image/*">
                                    <input type="file" name="image_3" id="image-3" class="img-file" accept="image/*">
                                    <input type="file" name="image_4" id="image-4" class="img-file" accept="image/*">
                                    <input type="file" name="image_5" id="image-5" class="img-file" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{ route('pesticides.index') }}" class="btn btn-default">ยกเลิก</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_bottom')
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
@endsection