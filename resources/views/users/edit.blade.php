@extends('layouts.app')
@section('title', 'Edit: '.$user->name)
@section('content')

<div class = 'container'>
<div class="card uper">
  <div class="card-header">

    Edit User {{$user->name}}

   </div>
     <div class="card-body">
   @if(session()->get('success'))
     <div class="alert alert-success">
       {{ session()->get('success') }}
     </div><br />
   @endif
   @if ($errors->any())
     <div class="alert alert-danger">
       <ul>
           @foreach ($errors->all() as $error)
             <li>{{ $error }}</li>
           @endforeach
       </ul>
     </div><br />
   @endif


   <!-- Assign Roles To Users -->

   <div class="form-group">
   <h5>{{$user->name}} Roles</h5>
   </div>



               <form action="{{url('users/addRole')}}" method = "post">
                 {!! csrf_field() !!}
                 <input type="hidden" name = "user_id" value = "{{$user->id}}">
                 <div class = 'row'>
                 <div class="form-group col-md-6">
                   <select name="role_name" id="" class = "form-control">

                     @foreach($roles as $role)
                       @if($role != "SuperAdmin")
                     <option value="{{$role}}">{{$role}}</option>
                       @endif
                     @endforeach

                   </select>
                 </div>



                 <div class="form-group col-md-6">
                   <button class = 'btn btn-primary'>Add role</button>
                 </div>

               </form>
             </div>
               <!-- End Assign Roles To Users -->

               <div class="form-group">
               @foreach($userRoles as $role)

               <a class='btn btn-primary' href='{{url('users/removeRole')}}/{{($role->name)}}/{{$user->id}}'><i class="fas fa-trash-alt"></i> {{$role->name}}</a>


               @endforeach
             </div>


             <!-- Assign Permission To Users -->

             <div class="form-group">
             <h5>{{$user->name}} Permissions</h5>
             </div>



                         <form action="{{url('users/addPermission')}}" method = "post">
                           {!! csrf_field() !!}
                           <input type="hidden" name = "user_id" value = "{{$user->id}}">
                           <div class = 'row'>
                           <div class="form-group col-md-6">
                             <select name="permission_name" id="" class = "form-control">

                               @foreach($permissions as $permission)

                               <option value="{{$permission}}">{{$permission}}</option>

                               @endforeach

                             </select>
                           </div>




                           <div class="form-group col-md-6">
                             <button class = 'btn btn-primary'>Add Permission</button>
                           </div>

                         </form>
                       </div>
                         <!-- End Assign Permission To Users -->

                         <div class="form-group">
                         @foreach($userPermissions as $permission)

                         <a class='btn btn-primary' href='{{url('users/removePermission')}}/{{($permission->name)}}/{{$user->id}}'><i class="fas fa-trash-alt"></i> {{$permission->name}}</a>
                         {{-- <a class='btn btn-primary'><i class="fas fa-trash-alt"></i> {{$permission->name}}</a> --}}

                         @endforeach
                       </div>

   <!-- add and remove groups to user -->
      <div class="form-group">
        <h5>Add user to groups</h5>
      </div>

      <form action="{{url('users/addUserGroup')}}" method = "post">

        @csrf
        <input type="hidden" name = "user_id" value = "{{$user->id}}">
        <div class = 'row'>
          <div class="form-group col-md-6">
            <select name="group_id" id="" class = "form-control">
              @foreach($groups as $group)
              <option value="{{$group->id}}">{{$group->group_name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <button class='btn btn-primary'>Assign</button>
          </div>
        </div>
      </form>

      <div class="form-group">
        @foreach($userGroups as $userGroup)
        <a class='btn btn-primary' href='{{url('users/removeUserGroup')}}/{{$userGroup->id}}/{{$user->id}}' data-activates=''><i class="fas fa-trash-alt"></i> {{$userGroup->group_name}}</a>
        @endforeach
      </div>
   <!-- end add and remove groups to user -->

  			<form action="{{ route('users.update', $user->id) }}" method = "post">
          @method('PATCH')
          @csrf
  				<input type="hidden" name = "user_id" value = "{{$user->id}}">

          <div class="form-group">
            <label for="name">KSAU-HS Email</label>
            <input type="text" class="form-control" name="email" value="{{$user->email}}"/>
          </div>

          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="{{$user->name}}"/>
          </div>

          <div class="form-group">
            <label for="name">Password</label>
            <input type="password" class="form-control" name="password" />
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
  			</form>




  			</div>
  		</div>

  	</div>
    </div>

@endsection
