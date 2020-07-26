@extends('layouts.app')
@section('title', 'Create Group')
@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>

<div class = 'container'>
<div class="card uper">
  <div class="card-header">
    Add Share
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
      <form method="post" action="{{ route('group.store') }}">
          <div class="form-group">
              @csrf
              <label for="name">group name</label>
              <input type="text" class="form-control" name="group_name"/>
          </div>
          <div class="form-group">
              <label for="price">group description</label>
              <input type="text" class="form-control" name="group_description"/>
          </div>
          <button type="submit" class="btn btn-primary">Add</button>
      </form>
  </div>
</div></div>
@endsection