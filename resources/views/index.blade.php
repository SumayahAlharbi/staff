@extends('layouts.app')

@section('content')
<script>
    var GOOGLE_API = '{!! env("GOOGLE_MAPS_STATIC_API") !!}';
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
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
                          <button id="attendBtn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Punch In
                          </button>
                        </p>
                        {{-- <span class="badge badge-secondary"> Attendance available for 15  minutes every hour </span> --}}
                        <p>
                          <div id="mapholder"></div>
                        </p>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @push('geolocation') --}}
<script type="text/javascript" src="{{ secure_url('js/geolocation.js') }}"></script>
{{-- @endpush --}}
@endsection

