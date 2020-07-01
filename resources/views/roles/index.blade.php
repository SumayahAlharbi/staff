@extends('layouts.app')
@section('title', 'Roles')
@section('content')

<div class = 'container'>
  <div class="row">
<div class="col">
<a class="btn btn-primary" href="{{ route('roles.create')}}" role="button">New +</a>
</div>
</div>
<button type="button" class="btn btn-link"></button>
<div class="card uper">
  <div class="card-header">
   All roles
  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif
	<table class="table table-striped">
		<head>
			<th>Role</th>
			<th>Permissions</th>
			<th>Actions</th>
      <th></th>
		</head>
		<tbody>
			@foreach($roles as $role)
			<tr>
				<td>{{$role->name}}</td>
				<td>
				@if(!empty($role->permissions))
					@foreach($role->permissions as $permission)
					<small class='btn btn-primary'>{{$permission->name}}</small>
					@endforeach
				@endif
				</td>
				<td>
					<a href="{{ route('roles.edit',$role->id)}}" class = "btn btn-primary">edit</a>
				</td>
        <td>
          <a href="{{url('/roles/delete')}}/{{$role->id}}" class = "btn btn-danger"><i class="fas fa-trash-alt"></i></a>
        </td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div></div>
@endsection
