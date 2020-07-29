@extends('layouts.app')
@section('title', 'Edit: ' . $group->group_name)
@section('content')

<div class = 'container'>
<div class="card uper">
  <div class="card-header">
    Edit group
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
      <form method="post" action="{{ route('group.update', $group->id) }}">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">group name:</label>
          <input type="text" class="form-control" name="group_name" value="{{ $group->group_name }}" />
        </div>
        <div class="form-group">
          <label for="price">group description:</label>
          <input type="text" class="form-control" name="group_description" value="{{ $group->group_description }}" />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
  </div>
</div>
</div>
@endsection