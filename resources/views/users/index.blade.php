@extends('layouts.app')
@section('title', 'Users')
@section('content')

<div class='container'>
  <div class="row">
    <div class="col">
      <a class="btn btn-primary" href="{{ route('users.create')}}" role="button">New <i class="icon ion-md-add-circle"></i></a>
    </div>
    <div class="col">
      <form method="get" action="{{ route('user.userSearch') }}">
        <div class="input-group">
          <label class="sr-only">Search</label>
          <div class="input-group">
            <input type="text" name="searchKey" class="form-control" placeholder="Search">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <span class="fas fa-search"></span></button>

            </div>
          </div>
        </div>
      </form>
    </div>

  </div>
<button type="button" class="btn btn-link"></button>

<div class="card uper">
  <div class="card-header">
   All users

  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

  <table class="table">
    <thead>
        <tr>
          <td>User</td>
          <td>Email</td>
          <td>Group</td>
          <td>Roles</td>
          <td>Permissions</td>
        </tr>
    </thead>
    <tbody>

  @foreach($users as $user)
    <tr>

    {{-- <td class="svg-avatar">{!! Avatar::create($user->name)->setFontSize(14)->setDimension(30, 30)->toSvg(); !!}  <a href="{{url('/users')}}/{{$user->id}}/edit" class="collection-item">{{$user->name}}</a></td> --}}
    <td> <a href="{{url('/users')}}/{{$user->id}}/edit" class="collection-item">{{$user->name}}</a></td>

    <td class="muted-text">
      {{$user->email}}
    </td>

    <td>
        @if(!empty($user->group->first()))
        @foreach($user->group as $userGroup)
          {{$userGroup->group_name}} <br>
        @endforeach
          @else

            No Groups

          @endif
    </td>

    <td>

        @if(!empty($user->roles))
        @foreach($user->roles as $role)

        <span class="new badge" data-badge-caption="">
          {{$role->name}}
        </span>

        @endforeach
        @else
        <span class="new badge grey" data-badge-caption="">
          No Roles
        </span>
        @endif
    </td>

    <td>

        @if(!empty($user->permissions))
        @foreach($user->permissions as $permission)

        <span class="new badge" data-badge-caption="">
          {{$permission->name}}
        </span>

        @endforeach
        @else
        <span class="new badge grey" data-badge-caption="">
          No Direct Permissions
        </span>
        @endif
    </td>

  </tr>
    @endforeach

  </tbody>
  <tfoot>
      <tr>
          <td colspan="6">
              <div class="text-right">
                  <ul class="pagination pagination-centered hide-if-no-paging"> </ul>
              </div>
          </td>
      </tr>
  </tfoot>
  </table>
  <div class="row">
    <div class="col-md-12">
{{ $users->onEachSide(1)->links() }}
  </div>
  </div>

</div>
{{-- {!! $users->render() !!} --}}
</div>
@endsection
