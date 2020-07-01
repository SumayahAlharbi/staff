@extends('layouts.app')
@section('title', 'Edit: ' . $role->name)
@section('content')
<div class="container">
<div class="card uper">
  <div class="card-header">
			<h3>Edit Role</h3>
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
			<form method="post" action="{{ route('roles.update', $role->id) }}">
				@method('PATCH')
        @csrf
				<div class="form-group">
				<label for="">Role</label>
					<input type="text" name = "name" class = "form-control" placeholder = "Name" value = "{{$role->name}}">
				</div>
        <button type="submit" class="btn btn-primary">Update</button>
			</form>

					<h3>{{$role->name}} Permissions</h3>

					<form action="{{url('roles/addPermission')}}" method = "post">
						{!! csrf_field() !!}
						<input type="hidden" name = "role_id" value = "{{$role->id}}">
						<div class="form-group">
							<select name="permission_name" id="" class = "form-control">
								@foreach($permissions as $permission)
								<option value="{{$permission}}">{{$permission}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<button class = 'btn btn-primary'>Add permission</button>
						</div>
					</form>
					<table class = 'table'>
						<thead>
							<th>Permission</th>
							<th>Action</th>
						</thead>
						<tbody>
							@foreach($RolePermissions as $permission)
							<tr>
								<td>{{$permission->name}}</td>
								{{-- <td><a href="{{url('roles/removePermission')}}/{{str_slug($permission->name,'-')}}/{{$role->id}}" class = "btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a></td> --}}
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
</div>
@endsection
