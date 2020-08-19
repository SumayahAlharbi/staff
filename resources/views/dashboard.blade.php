@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Teams & Members</div>
                <div class="card-body">
                <div class="button-box text-right">
                  @can('invite')
                    <a class="btn btn-outline-success" href="{{ route('invite') }}" title="Invite User To Your Team" role="button"><i class="fas fa-user-plus"></i></a>
                  @endcan
                </div>
                  <p>
              @foreach($groups as $group)
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title ">{{ $group->group_name }}</h4>
                    <hr class="mt-2 mb-3"/>
                  <p class="card-text">
                    @if($userGroup = $group)
                    @foreach($userGroup->user as $users)

                    <a class="badge badge-light" href={{url('/profile/' . $users->id)}}> {{$users->name}}</a>

                    @endforeach
                    @endif
                  </p>
                  </p>
                </div>
              </div>
              <br />
              @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
