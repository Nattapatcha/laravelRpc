@php
    $parameter = request()->route()->parameters();
@endphp

@extends('layouts.app')

@section('page_header', 'ข้อมูลสารกำจัดศัตรูพืช')

@section('content')
    @include('layouts.flash_message')

    <div id="paginate" class="row">
        <div class="col-md-6">
            {!! html()
        ->form(route('pesticides.index'))
        ->method('GET')
        ->class('form-inline')
        ->open() !!}

            <div class="form-group input-group">
                {!! html()
        ->text('keyword', request()->input('keyword'))
        ->class('form-control')
        ->placeholder('ค้นหาด้วยชื่อสามัญ ชื่อทางการค้า หรือชนิดพืช') !!}
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                </span>
            </div>

            {!! html()->form()->close() !!}
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>ชื่อสามัญ</th>
                <th>ชื่อการค้า</th>
                <th>กลุ่มสาร</th>
                <th>ชนิดพืช</th>
                <th class="text-right">
                    <a href="{{ route('pesticides.add') }}" class="btn btn-info btn-sm">
                        <i class="fa fa-plus"></i> เพิ่มข้อมูลสารกำจัดศัตรูพืช
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesticides as $pesticide)
                <tr>
                    <td>{{ ($pesticides->currentPage() - 1) * $pesticides->perPage() + $loop->iteration }}</td>
                    <td>{{ $pesticide->name }}</td>
                    <td>{{ $pesticide->trademark_name }}</td>
                    <td>{{ $pesticide->group->name ?? '-' }}</td>
                    <td>{{ $pesticide->plant }}</td>
                    <td>
                        <a href="{{ route('pesticides.edit', ['id' => $pesticide->id]) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-pencil"></i> แก้ไข
                        </a>
                        {!! html()->form('DELETE', route('pesticides.destroy', $pesticide->id))->style('display:inline')->open() !!}
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่?')">
                            <i class="fa fa-trash"></i> ลบ
                        </button>
                        {!! html()->form()->close() !!}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">ไม่พบข้อมูล</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $pesticides->appends(request()->only('keyword'))->links() }}

@endsection

@section('script_bottom')
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
@endsection