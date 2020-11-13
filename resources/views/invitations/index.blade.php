@extends('layouts.app')
@section('title', 'Invitations')
@section('content')

<div class = 'container'>
  <div class="row">
  <div class="col-12">
<div class="card uper">
  <div class="card-header">
  Invitations Inbox
  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif
  @if (count($invitations) > 0)
  <table class="table table-striped">
    <thead>
        <tr>
          <td>Group Name</td>
          <td>Description</td>
          <td>Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($invitations as $invitation)
        <tr>
            <td>{{$invitation->group->group_name}}</td>
            <td>{{$invitation->group->group_description}}</td>
            <td><a href="{{ route('accept',$invitation->token)}}" class="btn btn-success">Accept</a></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
      <tr>
          <td colspan="6">
              <div class="text-right">
                  <ul>   {{ $invitations->links() }} </ul>
              </div>
          </td>
      </tr>
  </tfoot>
  </table>
  @else
  <blockquote class="blockquote text-center">
  <p class="mb-0">You did not receive any invitation yet!</p>
  <footer class="text-muted">If you do not have a group, please notify the responsible in your department to send you an invitation</footer>
</blockquote>
      
      @endif
</div>
</div>
</div>
@endsection