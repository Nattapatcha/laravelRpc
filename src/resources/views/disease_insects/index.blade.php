@php
    $parameter = request()->route()->parameters();
@endphp

@extends('layouts.app')

@section('page_header', 'ข้อมูลโรคและแมลง')

@section('content')
    @include('layouts.flash_message')

    {!! html()->form('GET', route('disease_insects.index'))->open() !!}
    <div id="paginate" class="row">
        <div class="col-md-2">
            {!! html()->select('type', [
        '0' => 'ทั้งหมด',
        '1' => 'โรคพืช',
        '2' => 'แมลง'
    ])->class('form-control')->value(request()->input('type')) !!}
        </div>
        <div class="col-md-6">
            <div class="form-group input-group">
                {!! html()->text('keyword')
        ->class('form-control')
        ->placeholder('ค้นหาด้วยชื่อโรค')
        ->value(request()->input('keyword')) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}

    <table class="table">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>ประเภท</th>
                <th>ชื่อ</th>
                <th class="text-right">
                    <a href="{{ route('disease_insects.add') }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>
                        เพิ่มข้อมูลโรคและแมลง</a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($diseaseInsects as $index => $diseaseInsect)
                <tr>
                    <td>{{ ($diseaseInsects->currentPage() - 1) * $diseaseInsects->perPage() + $index + 1 }}</td>
                    <td>{{ $diseaseInsect->type_name }}</td>
                    <td>{{ $diseaseInsect->name }}</td>
                    <td>
                        <a href="{{ route('disease_insects.edit', ['id' => $diseaseInsect->id]) }}"
                            class="btn btn-success btn-sm"><i class="fa fa-pencil"></i> แก้ไข</a>
                        {!! html()
                ->form('DELETE', route('disease_insects.destroy', $diseaseInsect->id))
                ->style('display:inline')
                ->open() !!}
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่?')">
                            <i class="fa fa-trash"></i> ลบ
                        </button>
                        {!! html()->form()->close() !!}

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $diseaseInsects->appends(request()->query())->links() }}

@endsection

@section('script_bottom')
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
@endsection