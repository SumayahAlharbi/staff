@extends('layouts.app')
@section('title', 'Create User')
@section('content')

<div class = 'container'>
<div class="card uper">
  <div class="card-header">
    Create New User
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
      <form method="post" action="{{ route('users.store') }}">
          <div class="form-group">
              @csrf
              <div class="form-group">
              <label for="name">Email</label>
              <input type="text" class="form-control" name="email" value="{{ old('email') }}" required/>
              </div>
              <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}" required/>
              </div>
              <div class="form-group">
              <label for="name">Password</label>
              <input type="password" class="form-control" name="password" required/>
          </div>

          <button type="submit" class="btn btn-primary">Create</button>
      </form>
  </div>
</div></div>
@endsection
