@extends('layouts.app')
@section('title', 'Groups')
@section('content')

<div class = 'container'>
  <div class="row">
<div class="col">
<a class="btn btn-primary" href="{{ route('group.create')}}" role="button">New +</a>
</div>
</div>
<button type="button" class="btn btn-link"></button>
<div class="card uper">
  <div class="card-header">
   All Categories
  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif
  <table class="table table-striped">
    <thead>
        <tr>
          <td>ID</td>
          <td>Group Name</td>
          <td>Description</td>
          <td>Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($groups as $group)
        <tr>
            <td>{{$group->id}}</td>
            <td>{{$group->group_name}}</td>
            <td>{{$group->group_description}}</td>
            <td><a href="{{ route('group.edit',$group->id)}}" class="btn btn-primary">Edit</a></td>
            <td>
                <form onsubmit="return confirm('Do you really want to delete?');" action="{{ route('group.destroy', $group->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div></div>
@endsection