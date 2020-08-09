@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body"></div>
              @foreach($groups as $group)
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $group->group_name }}</h4>
                  <p class="card-text">
                    @if($userGroup =$group)
                    @foreach($userGroup->user as $users)

                    <p>{{$users->name}}</p>

                    @endforeach
                    @endif
                  </p>
                </div>
              </div>
              @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
