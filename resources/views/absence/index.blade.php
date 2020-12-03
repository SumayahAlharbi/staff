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
        <label for="Group">Group Name</label>
        <select class="form-control" name="group_id" />
        <option value="none" selected>Select Group </option>
        @foreach($userGroups as $group)
        <option value="{{$group->id}}"> {{$group->group_name}}</option>
        @endforeach
      </select>
      </div>
      <div class="form-group col-md-4">
          @csrf
          <label for="Date">Date</label>
          <input type="date" class="form-control" name="date" required/>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>
</div>

@isset($partiallyAbsent)
<br>
<div class="col-md-10"> Missing check in or check out of group: {{$group->group_name}}, date: {{\Carbon\Carbon::parse($date)->format('d-m-Y')}} </div>
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
      @foreach($partiallyAbsent as $absentsheet)
        <tr>
          <td>{{$absentsheet->name}}</td>
          <td>{{$absentsheet->email}}</td>
          <td>
            @if ($absentsheet->attendance[0]->action == 'Check In')
            Missing Check Out
            @elseif ($absentsheet->attendance[0]->action == 'Check Out')
            Missing Check In
            @endif
          </td>
          <td>{{ \Carbon\Carbon::parse($absentsheet->attendance[0]->created_at)->format('d-m-Y') }}</td>
      </tr>
        @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="6">
                  <div class="text-right">
                      <ul> {{ $partiallyAbsent->links() }} </ul>
                  </div>
              </td>
          </tr>
      </tfoot>
      </table>
  @endif


@isset($totallyAbsent)
<div class="col-md-10"> Absent members of group: {{$group->group_name}}, date: {{\Carbon\Carbon::parse($date)->format('d-m-Y')}} </div>
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
  @foreach($totallyAbsent as $key => $value)
    <tr>
      <td>{{$value->name}}</td>
      <td>{{$value->email}}</td>
      <td>
      Absent
      </td>
      <td>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
  </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <td colspan="6">
            <div class="text-right">
                <ul> {{ $totallyAbsent->links() }} </ul>
            </div>
        </td>
    </tr>
</tfoot>
</table>
@endif
  @endcan
</div>
</div>
@endsection
