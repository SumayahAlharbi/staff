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

  <table class="table table-striped">
    <thead>
        <tr>
          <td>Name</td>
          <td>Email</td>
          <td>Type</td>
          <td>Date/Time</td>
          <td>GPS</td>
        </tr>
    </thead>
    <tbody>

  @foreach($attendancesheets as $attendancesheet)
    <tr>
      <td>{{$attendancesheet->user->name}}</td>
      <td>{{$attendancesheet->user->email}}</td>
      <td>{{$attendancesheet->action}}</td>
      <td>{{$attendancesheet['created_at']}} <b>({{$attendancesheet['created_at']->diffForHumans()}})</b></td>
      <td><a target="_blank" href="https://www.google.com/search?q={{$attendancesheet->coords}}">Map</a></td>
  </tr>
    @endforeach

  </tbody>
  <tfoot>
      <tr>
          <td colspan="6">
              <div class="text-right">
                  <ul> {{ $attendancesheets->links() }} </ul>
              </div>
          </td>
      </tr>
  </tfoot>
  </table>

</div>
{{-- {!! $users->render() !!} --}}
</div>
@endsection
