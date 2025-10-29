@php /** @var \Illuminate\Pagination\LengthAwarePaginator $users */ @endphp
@extends('layouts.app')

@section('page_header', 'เกษตรกร')

@section('content')
  @include('layouts.flash_message')

  <form method="GET" action="{{ route('agriculturists.index') }}">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group input-group">
          <input name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="ค้นหาชื่อหรืออีเมล">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </div>
      <div class="col-md-6 text-right">
        <a href="{{ route('employees.index') }}" class="btn btn-link">ไปหน้าพนักงาน</a>
      </div>
    </div>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>ลำดับที่</th>
        <th>ชื่อ - นามสกุล</th>
        <th>รหัสบัตรประชาชน</th>
        <th>หน่วยงาน </th>
        <th>วันที่สร้าง</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
        <tr>
          <td>{{ ($users->firstItem() ?? 0) + $loop->index }}</td>
          <td>{{ trim(($u->name ?? '') . ' ' . ($u->surname ?? '')) }}</td>
          <td>{{ $u->idcard ?? '-' }}</td>
          <td>{{ $u->org->name ?? '-' }}</td>
          <td>{{ optional($u->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">ไม่พบข้อมูล</td>
        </tr>
      @endforelse
    </tbody>
  </table>


  {{ $users->appends(request()->query())->links() }}
@endsection