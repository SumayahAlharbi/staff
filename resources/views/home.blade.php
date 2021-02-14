@extends('layouts.app')

@section('content')
<script>
  var GOOGLE_API = '{!! env("GOOGLE_MAPS_STATIC_API") !!}';
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Attendance</div>

                <div class="card-body">
                  @if(session()->get('success'))
                    <div class="alert alert-success">
                      {{ session()->get('success') }}
                    </div><br />
                  @endif
                  @if(session()->get('danger'))
                  <div class="alert alert-danger">
                    {{ session()->get('danger') }}
                  </div><br />
                  @endif
                  @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                    </div><br />
                  @endif
                    <div id="tripmeter">
                      {{-- <p>
                        Starting Location (lat, lon):<br/>
                        <span id="startLat">???</span>&deg;, <span id="startLon">???</span>&deg;
                      </p>
                      <p>
                        Current Location (lat, lon):<br/>
                        <span id="currentLat">locating...</span>&deg;, <span id="currentLon">locating...</span>&deg;
                      </p>
                      <p>
                        Distance from starting location:<br/>
                        <span id="distance">0</span> km
                      </p>
                      <p>
                        Location accuracy:<br/>
                        <span id="currentAcc">0</span> M
                      </p> --}}
                      <p>
                        Are we here?<br/>
                        <span id="message"><i id="spinner" class="fa fa-spinner fa-pulse"></i></span>

                      </p>
                      <p>
                        <!-- Tutorial -->

                        <div id="highlight" class="alert alert-danger">
                          <div id="error"></div>
                          <div id="tutorial">
                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#tutorialModal">
                              To Enable location check here
                            </button>

                            <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="tutorial" data-dismiss="modal" aria-label="Close" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">

                                  <div class="modal-header">
                                    <h5 class="modal-title" id="tutorialModal">Enable Geolocation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>

                                  <div class="modal-body">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                      <li class="nav-item active">
                                        <a class="nav-link in active" id="iphone-tab" data-toggle="tab" href="#iphone" role="tab" aria-controls="home" aria-selected="true">iPhone</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" id="androied-tab" data-toggle="tab" href="#android" role="tab" aria-controls="profile" aria-selected="false">Android</a>
                                      </li>
                                    </ul>

                                    <div class="tab-content">
                                      <div id="iphone" class="tab-pane fade show active">
                                        <br>
                                        <b style="font-size:15px" >To Enable Location Services Please Follow the Instructions:</b>
                                        <p style="font-size:15px" >Settings > privacy > location Services > Safari > While Using the App</p>
                                        <img src="/images/iphone1.png" style="width:165.9px;height:317.8px;">
                                        <img src="/images/iphone2.png" style="width:165.9px;height:317.8px;">
                                      </div>
                                      <div id="android" class="tab-pane fade">
                                        <br>
                                        <b style="font-size:15px" >To Enable Location Services Please Follow the Instructions:</b>
                                        <p style="font-size:15px" >Settings > site Settings > Location > On</p>
                                        <img src="/images/android1.png"style="width:165.9px;height:317.8px;">
                                        <img src="/images/android2.png"style="width:165.9px;height:317.8px;">
                                        <img src="/images/android3.png"style="width:165.9px;height:317.8px;">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- /Tutorial -->
                          </div>
                        </div>

                        <div id="success" class="alert alert-success"></div>

                      </p>
                      <p>
                        <div id="attendBtn">
                        <form method="post" action="{{ route('attendancesheet.store') }}">
                          @csrf
                        <button type="submit" name="Action" value="Check In" class="btn btn-primary btn-lg btn-block p-5" onclick="return confirm('Are you sure to check in?');">Check In</button>
                        <button type="submit" name="Action" value="Check Out" class="btn btn-secondary btn-lg btn-block p-5" onclick="return confirm('Are you sure to check out?');">Check Out</button>

                        <div class="form-group">
                            <label for="FormControlSelect">Group</label>
                            <select required class="form-control" name="group_id">
                                @if(count($user->group) > 1)
                              <option value="">None</option>
                                @endif
                              @foreach ($groups as $group)
                                <option value="{{$group->id}}">{{$group->group_name}}</option>
                                @endforeach
                            </select>
                          </div>

                        <div id="coords"></div>
                      </form>
                    </div>
                      </p>
                      {{-- <span class="badge badge-secondary"> Attendance available for 15  minutes every hour </span> --}}
                      <p>
                        <div id="mapholder"></div>
                      </p>
                    </div>
                    <p>Your latest attendance:</p>
          <table class="table table-striped">
            <thead>
              <tr>
                <td>Attendance Type</td>
                <td>Group</td>
                <td>Timestamp</td>
              </tr>
            </thead>
            <tbody>
              @foreach($UserAttendance as $Attendance)
              <tr>
                <td>{{$Attendance->action}}</td>
                <td>{{$Attendance->group['group_name']}}</td>
                <td>{{$Attendance['created_at']}} ({{$Attendance['created_at']->diffForHumans()}})</td>
              </tr>
              @endforeach
            </tbody>
          </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @push('geolocation') --}}
<script type="text/javascript" src="{{ secure_url('js/geolocation.js') }}"></script>
{{-- @endpush --}}
@endsection
