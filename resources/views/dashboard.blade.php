@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
              <div class="card-header">Teams & Members
                @can('invite')
                <div class="button-box float-right">
                  <a class="btn btn-outline-success" href="{{ route('invite') }}" title="Invite User To Your Team" role="button"><i class="fas fa-user-plus"></i></a>
                </div>
                @endcan
            </div>


            <div class="col-12 mt-4">
                <div class="float-right">
                  <span class="badge badge-primary">&nbsp;&nbsp;</span> Checked In
                  <span class="badge badge-secondary">&nbsp;&nbsp;</span> Checked Out
                </div>
            </div>

                <div class="card-body">
                  <p>
              @foreach($groups as $group)
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $group->group_name }}</h4>
                    <hr class="mt-2 mb-3"/>
                  <p class="card-text">
                    @foreach($group->user as $key => $index)
                    @if($index->lastAction != 'none')
                    @if($index->lastAction->action == 'Check In')
                    <a class="badge badge-primary" href={{url('/profile/' . $index->id)}}> {{$index->name}}</a>
                    @elseif($index->lastAction->action == 'Check Out')
                    <a class="badge badge-secondary" href={{url('/profile/' . $index->id)}}> {{$index->name}}</a>
                    @endif
                    @else
                    <a class="badge badge-light" href={{url('/profile/' . $index->id)}}> {{$index->name}}</a>
                    @endif
                    @endforeach
                  </p>
                </div>
              </div>
            </p>
              <br />
              @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
