@extends('layouts.app')
@section('title', 'Absence Sheet')
@section('content')

<div class = 'container'>

<div class="card uper">
  <div class="card-header">
   Absence Sheet
  </div>

  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

  @can('export')
  <div class="card">
    <div class="card-body">
  <form method="post" action="{{ route('absence') }}">
    <div class="row">
      <div class="form-group col-md-4">
        <label for="from_date">Group Name</label>
        <select class="form-control" name="group_id" />
        <option value="none" selected>Select Group </option>
        @foreach($userGroups as $group)
        <option value="{{$group->id}}"> {{$group->group_name}}</option>
        @endforeach
      </select>
      </div>

      {{--<div class="form-group col-md-2">
          <label for="from_date">Check in</label>
          <input type="checkbox" class="form-control" name="check_in" />
      </div>
      <div class="form-group col-md-2">
        <label for="to_date">Check out</label>
        <input type="checkbox" class="form-control" name="check_out" />
      </div>--}}

      <div class="form-group col-md-4">
          @csrf
          <label for="from_date">From</label>
          <input type="date" class="form-control" name="from_date" required/>
      </div>
      <div class="form-group col-md-4">
        <label for="to_date">To</label>
        <input type="date" class="form-control" name="to_date" required/>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>
</div>
  @endcan

@isset($absentsheets)
  <table class="table table-striped">
    <thead>
        <tr>
          <td>Name</td>
          <td>Email</td>
          <td>Type</td>
          <td>Date</td>
        </tr>
    </thead>
    <tbody>

      @foreach($absentsheets as $absentsheet)
        <tr>
          <td>{{$absentsheet->name}}</td>
          <td>{{$absentsheet->email}}</td>
          <td>
            @isset($absentsheet->attendance[0])
            @if ($absentsheet->attendance[0]->action == 'Check In')
            Check Out
            @elseif ($absentsheet->attendance[0]->action == 'Check Out')
            Check In
            @endif
            @else
            No Records
            @endif
          </td>
          <td>{{\Carbon\Carbon::parse($absentsheet->attendance[0]->created_at)->format('d-m-Y')}}</td>
      </tr>
        @endforeach

      </tbody>
      <tfoot>
          <tr>
              <td colspan="6">
                  <div class="text-right">
                      <ul> {{ $absentsheets->links() }} </ul>
                  </div>
              </td>
          </tr>
      </tfoot>
      </table>
  @endif
</div>
</div>
@endsection
