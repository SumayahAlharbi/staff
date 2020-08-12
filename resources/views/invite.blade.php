@extends('layouts.app')
@section('title', 'Create Permission')
@section('content')

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
      </ul>
    </div><br />
  @endif

<div class = 'container'>
  <div class="row">
      <div class="col-12">
          <div class="card">
            <div class="card-header">
              Invite
            </div>
              <div class="card-body">
                <form method="post" action="{{ route('invite') }}">
                    <div class="form-group">
                        @csrf
                        <label for="name">Email</label>
                        <input type="email" name = "email" class = "form-control" placeholder = "email">
                    </div>

                    <button type="submit" class="btn btn-primary">Invite</button>
                </form>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
