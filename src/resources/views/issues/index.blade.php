@php 
    $parameter = request()->route()->parameters(); 
    /** @var \Illuminate\Pagination\LengthAwarePaginator $issues */
@endphp

@extends('layouts.app')

@section('page_header', 'รายงานปัญหา')

@section('content')
    @include('layouts.flash_message')

    <div class="row">
        <div class="col-md-6">
            {!! html()->form('GET', route('issues.index'))->open() !!}
            <div class="form-group input-group">
                {!! html()->text('keyword')
        ->class('form-control')
        ->placeholder('ค้นหาด้วยชื่อ-นามสกุล หรือ รหัสเกษตรกร')
        ->value(request()->input('keyword')) !!}
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
                <th>ชื่อ-นามสกุล</th>
                <th>รหัสเกษตรกร</th>
                <th>ศุนย์/สถานี</th>
                <th>พืช</th>
                <th>ชนิดศัตรู</th>
                <th>วันที่แจ้งปัญหา</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($issues as $issue)
                <tr>
                    {{-- ลำดับ: กันเคสว่างด้วย firstItem() --}}
                    <td>{{ ($issues->firstItem() ?? 0) + $loop->index }}</td>

                    <td>{{ $issue->full_name }}</td>
                    <td>{{ $issue->agriculturist_code }}</td>
                    <td>{{ $issue->station }}</td>
                    <td>{{ $issue->plant_type }}</td>
                    <td>{{ $issue->disease_insects }}</td>
                    <td>{{ optional($issue->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('issues.show', ['id' => $issue->id]) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-eye"></i> ดูข้อมูล
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">ไม่พบข้อมูล</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $issues->links() }}

@endsection

@section('script_bottom')
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
@endsection