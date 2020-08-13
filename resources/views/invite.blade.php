@extends('layouts.app')
@section('title', 'Create Permission')
@section('content')

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
      </ul>
    </div><br />
  @endif

<div class = 'container'>
  <div class="row">
      <div class="col-12">
          <div class="card">
            <div class="card-header">
              Invite
            </div>
            @if(session()->get('success'))
              <div class="alert alert-success">
                {{ session()->get('success') }}
              </div><br />
            @endif
              <div class="card-body">
                <form method="post" action="{{ route('invite') }}">

                    <div class="form-group">
                      @csrf
                        <label for="FormControlSelect">Users</label>
                        <select required class="form-control" name="email">
                          <option value="">None</option>
                          @foreach ($users as $user)
                            <option value="{{$user->email}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                      </div>
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

                    <button type="submit" class="btn btn-primary">Invite</button>
                </form>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
