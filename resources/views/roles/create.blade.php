@extends('layouts.app')
@section('title', 'Create Role')
@section('content')

<div class = 'container'>
<div class="card uper">
  <div class="card-header">
    Add Role
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('roles.store') }}">
          <div class="form-group">
              @csrf
              <label for="name">role name</label>
							<input type="text" name = "name" class = "form-control" placeholder = "Name">
          </div>

          <button type="submit" class="btn btn-primary">Create</button>
      </form>
  </div>
</div></div>
@endsection
