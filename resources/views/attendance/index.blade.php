@extends('layouts.app')
@section('title', 'Attendance Sheet')
@section('content')

<div class = 'container'>

{{-- <button type="button" class="btn btn-link"></button> --}}

<div class="card uper">
  <div class="card-header">
   Attendance Sheet

  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

  @can('export')
  <!--<div class="float-left"> <a class="btn btn-primary" href="{{ route('attendancesheet.export') }}" role="button">Export</a> </div>-->
  <div class="card">
    <div class="card-body">
  <form method="post" action="{{ route('attendancesheet.export') }}">
    <div class="row">
      <div class="form-group col-md-4">
          @csrf
          <label for="from_date">from</label>
          <input type="date" class="form-control" name="from_date" />
      </div>
      <div class="form-group col-md-4">
        <label for="to_date">to</label>
        <input type="date" class="form-control" name="to_date" />
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="sort_by">Sort by</label>
          <select class="form-control" name="sort_by">
              <option value="id">id</option>
          </select>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Generate & Download</button>
  </form>
</div>
</div>
  @endcan
  <livewire:update-attendance>
  

</div>
{{-- {!! $users->render() !!} --}}
</div>
@endsection
