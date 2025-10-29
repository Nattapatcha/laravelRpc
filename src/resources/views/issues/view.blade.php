@extends('layouts.app')
@section('page_header', 'รายงานปัญหา')

@section('style_top')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('layouts.flash_message')

    <div id="map-modal" class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">รายงานปัญหา</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label('ชื่อ-นามสกุล', 'full_name') !!}
                                        <div class="input">{{ $issue->full_name }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label('รหัสเกษตรกร', 'agriculturist_code') !!}
                                        <div class="input">{{ $issue->agriculturist_code }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label('Lat/Long', 'lat_long') !!}
                                        <div class="input">{{ $issue->lat }}, {{ $issue->long }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label('ศูนย์/สถานี', 'station') !!}
                                        <div class="input">{{ $issue->station }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('พืช', 'plant_type') !!}
                                        <div class="input">{{ $issue->plant_type }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! html()->label('ชนิดศัตรู', 'disease_insects') !!}
                                        <div class="input">{{ $issue->disease_insects }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! html()->label('% ศัตรูพืช/พื้นที่ทั้งหมด', 'area_percentage') !!}
                                        <div class="input">{{ $issue->area_percentage }}%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group images-container">
                                        {!! html()->label('รูปภาพ', 'media_images') !!}
                                        @for($i = 0; $i < 5; $i++)
                                            @if (isset($issue->media[$i]))
                                                @php
                                                    $media = $issue->media[$i];
                                                    $imagePath = route('files.images', ['file' => $media->image]);
                                                @endphp
                                                <a href="{{ $imagePath }}" target="_blank">
                                                    <img class="img-issue-preview" src="{{ $imagePath }}">
                                                </a>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    {!! html()->label('แผนที่', 'map') !!}
                                    <div id="map"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            

                            @php $locked = filled($issue->reply); @endphp
                            {{-- แสดงคำตอบล่าสุด (ถ้ามี) --}}
                            @if($locked)
                                <h4>ตอบกลับผู้แจ้ง</h4>
                                <div class="alert alert-success">
                                    <small class="text-muted">คำตอบล่าสุด</small>
                                    <div>{!! nl2br(e($issue->reply)) !!}</div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('issues.reply', $issue->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="reply">พิมพ์ข้อความตอบกลับ</label>
                                    <textarea id="reply" name="reply" rows="3" class="form-control" @if($locked) readonly
                                    @endif required>{{ old('reply', $issue->reply) }}</textarea>
                                    @error('reply') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                @unless($locked)
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-send"></i> บันทึกคำตอบ
                                    </button>
                                @else
                                    <div class="text-muted small">หากตอบกลับแล้ว ไม่สามารถแก้ไขได้อีก</div>
                                @endunless
                                    <a class="btn btn-default" href="{{ route('issues.index') }}">กลับ</a>
                                
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
@endsection

    @section('script_bottom')
            <script type="text/javascript">
                window.global = {
                    position: {!! json_encode([
            'lat' => (float) $issue->lat,
            'lng' => (float) $issue->long
        ]) !!}
                }
            </script>
            <script defer src="https://maps.googleapis.com/maps/api/js?key=?"></script>
    @endsection