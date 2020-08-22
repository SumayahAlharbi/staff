@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row">
    <div class="col-lg-4 col-xlg-3 col-md-5">
      <div class="card">
        <div class="card-body">
          <center class="m-t-30">
            <h4 class="card-title m-t-10">{{$user->name}}</h4>
            <h6 class="card-subtitle">{{$user->email}}</h6>
            <div class="row text-center justify-content-md-center">
              @if(count($user->group) > 0)
              @foreach($user->group as $group)
              <span class="label label-light-inverse" title="{{$group->group_description}}">{{$group->group_name}}</span>
              @endforeach
              @endif
            </div>
          </center>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-xlg-9 col-md-7">
      <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab" role="tablist">
          <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#userAttendance" role="tab">Attendance</a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="userAttendance" role="tabpanel">
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    {{--<td>Name</td>--}}
                    {{--<td>Email</td>--}}
                    <td>Type</td>
                    <td>Group</td>
                    <td>Date/Time</td>
                    <td>GPS</td>
                  </tr>
                </thead>
                <tbody>

                  @foreach($attendancesheets as $attendancesheet)
                  <tr>
                    {{--<td>{{$attendancesheet->user->name}}</td>--}}
                    {{--<td>{{$attendancesheet->user->email}}</td>--}}
                    <td>{{$attendancesheet->action}}</td>
                    <td>{{$attendancesheet->group['group_name']}}</td>
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
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
