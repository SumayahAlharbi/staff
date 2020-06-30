@extends('layouts.app')
@section('title', 'Attendance Sheet')
@section('content')

<div class = 'container'>

<button type="button" class="btn btn-link"></button>

<div class="card uper">
  <div class="card-header">
   Attendance Sheet

  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

  <table class="table table-striped">
    <thead>
        <tr>
          <td>Name</td>
          <td>Email</td>
          <td>Date/Time</td>
          <td>GPS</td>
        </tr>
    </thead>
    <tbody>

  @foreach($attendancesheets as $attendancesheet)
    <tr>
      <td>{{$attendancesheet->user->name}}</td>
      <td>{{$attendancesheet->user->email}}</td>
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
