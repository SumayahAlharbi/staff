@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Attendance</div>

                <div class="card-body">
                  <div id="success" class="alert alert-success"></div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('attendancesheet.store') }}">
                      @csrf
                    <button type="submit" name="Action" value="Check In" class="btn btn-primary btn-lg btn-block p-5">Check In</button>
                    <button type="submit" name="Action" value="Check Out" class="btn btn-secondary btn-lg btn-block p-5">Check Out</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
