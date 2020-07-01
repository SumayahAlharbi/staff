@extends('layouts.app')
@section('title', 'Edit: ' . $permissions->name)
@section('content')

<div class = 'container'>
<div class="card uper">
  <div class="card-header">
    Edit Permission
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
      <form method="post" action="{{ route('permissions.update', $permissions->id) }}">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Permission Name</label>
          <input type="text" class="form-control" name="name" value="{{ $permissions->name }}" />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
  </div>
</div>
</div>
@endsection
