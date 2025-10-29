@php
    if (!isset($diseaseInsect)) {
        $header = 'เพิ่มข้อมูลโรคพืชและแมลง';
        $url = route('disease_insects.store');
    } else {
        $header = 'แก้ไขข้อมูลโรคพืชและแมลง';
        $url = route('disease_insects.update', ['id' => $diseaseInsect->id]);
    }
@endphp

@extends('layouts.app')
@section('page_header', $header)

@section('style_top')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet" type="text/css">
@endsection

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
                    {!! html()->form(!isset($diseaseInsect) ? 'POST' : 'PUT', $url)->acceptsFiles()->open() !!}
                    @csrf
                    @if(isset($diseaseInsect)) @method('PUT') @endif
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('ชื่อ', 'name') !!}
                                        {!! html()->input('text', 'name')
        ->id('name')
        ->class('form-control')
        ->value(old('name', optional($diseaseInsect)->name)) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label('ประเภท', 'type-sel') !!}
                                        {!! html()->select('type')
        ->id('type-sel')
        ->class('form-control')
        ->options([
            '1' => 'โรคพืช',
            '2' => 'แมลง'
        ])
        ->value(old('type', optional($diseaseInsect)->type)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row disease-form-group">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('เชื้อสาเหตุ', 'cause') !!}
                                        {!! html()->textarea('cause')->id('cause')->class('form-control')->rows(5)->value(old('cause', optional($diseaseInsect)->cause)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row disease-form-group">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('ลักษณะอาการ', 'symptom') !!}
                                        {!! html()->textarea('symptom')->id('symptom')->class('form-control')->rows(8)->value(old('symptom', optional($diseaseInsect)->symptom)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row insect-form-group" style="display:none;">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('วงจรชีวิต', 'life_cycle') !!}
                                        {!! html()->textarea('life_cycle')->id('life_cycle')->class('form-control')->rows(5)->value(old('life_cycle', optional($diseaseInsect)->life_cycle)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row insect-form-group" style="display:none;">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('การทำลาย', 'effect') !!}
                                        {!! html()->textarea('effect')->id('effect')->class('form-control')->rows(8)->value(old('effect', optional($diseaseInsect)->effect)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('การป้องกันกำจัด', 'protect_eliminate') !!}
                                        {!! html()->textarea('protect_eliminate')->id('protect_eliminate')->class('form-control')->rows(8)->value(old('protect_eliminate', optional($diseaseInsect)->protect_eliminate)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('ข้อมูลพืช', 'plant_type') !!}
                                        {!! html()->textarea('plant_type')->id('plant_type')->class('form-control')->rows(5)->value(old('plant_type', optional($diseaseInsect)->plant_type)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! html()->label('รูป')->class('form-label')->id('img-label') !!}
                                <div class='flex-container'>
                                    <div class="row">
                                        @if(isset($diseaseInsect) && $diseaseInsect->media && $diseaseInsect->media->isNotEmpty())
                                            @foreach($diseaseInsect->media->take(5) as $media)
                                                <div class="col">
                                                    <div class="img-upload-container">
                                                        <div data-id="{{ $media->id }}" class="delete-img-btn">X</div>
                                                        <div class="img-container">
                                                            <img class="img-thumb"
                                                                src="{{ $media->image_url ?? asset('images/no-image.svg') }}"
                                                                loading="lazy" width="240" height="160"
                                                                alt="{{ $diseaseInsect->name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                {!! html()->label('อัพโหลดไฟล์', 'image-1')->class('form-label')->id('img-upload-label') !!}
                                {!! html()->file('image_1')->id('image-1')->class('img-file')->accept('image/*') !!}
                                {!! html()->file('image_2')->id('image-2')->class('img-file')->accept('image/*') !!}
                                {!! html()->file('image_3')->id('image-3')->class('img-file')->accept('image/*') !!}
                                {!! html()->file('image_4')->id('image-4')->class('img-file')->accept('image/*') !!}
                                {!! html()->file('image_5')->id('image-5')->class('img-file')->accept('image/*') !!}

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">บันทึก</button>
                                <a class="btn btn-default" href="{{ route('disease_insects.index') }}">ยกเลิก</a>
                            </div>
                        </div>
                    </div>
                    {!! html()->form()->close() !!}
                </div>
            </div>
        </div>
@endsection

    @section('script_bottom')
        <script>
            (function () {
                const sel = document.getElementById('type-sel');
                if (!sel) return;

                const disease = Array.from(document.querySelectorAll('.disease-form-group'));
                const insect = Array.from(document.querySelectorAll('.insect-form-group'));

                function apply() {
                    const v = String(sel.value || '');
                    const showDisease = (v === '1');
                    disease.forEach(el => el.style.display = showDisease ? '' : 'none');
                    insect.forEach(el => el.style.display = showDisease ? 'none' : '');
                }

                sel.addEventListener('change', apply);
                apply(); // init on first load (works with old() / edit)
            })();
        </script>
        <script src="{{ asset('js/bootbox.min.js') }}"></script>
    @endsection