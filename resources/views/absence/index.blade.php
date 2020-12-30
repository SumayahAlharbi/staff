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
  <form method="get" action="{{ route('absencesheet') }}">
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
          
          <label for="Date">Date</label>
          <input type="date" class="form-control" name="date" required/>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
    @if(isset($partiallyAbsent) OR isset($totallyAbsent))
    <a href="{{route('absencesheet.export', ['group_id' => $group_id, 'date'=> $date])}}" class="btn btn-success">Export</a>
    @endif
  </form>
</div>
</div>
@isset($partiallyAbsent)
<div role="group" class="row mb-2 mt-2 ml-2">
      <h5 class="mr-3"><span class="badge badge-secondary"> Group: {{$group_name}}</span></h5>
      <h5><span class="badge badge-secondary"> Date: {{\Carbon\Carbon::parse($date)->format('d-m-Y')}}</span></h5>
    </div>

  <table class="table table-striped">
    <thead>
        <tr>
          {{--<td>Group</td>--}}
          <td>Name</td>
          <td>Email</td>
          <td>Type</td>
          {{--<td>Date</td>--}}
        </tr>
    </thead>
    <tbody>
      @foreach($partiallyAbsent as $absentsheet)
        <tr>
          <td>{{$absentsheet->name}}</td>
          <td>{{$absentsheet->email}}</td>
          <td>
            @if ($absentsheet->action == 'Check In')
            Missing Check Out
            @elseif ($absentsheet->action == 'Check Out')
            Missing Check In
            @endif
          </td>
      </tr>
        @endforeach
        @foreach($totallyAbsent as $key => $value)
          <tr>
            <td>{{$value->name}}</td>
            <td>{{$value->email}}</td>
            <td>
            Absent
            </td>
        </tr>
          @endforeach
      </tbody>
      {{--<tfoot>
          <tr>
              <td colspan="6">
                  <div class="text-right">
                      <ul> {!! $partiallyAbsent->appends(['group_id' => $group->id, 'date' => $date])->render() !!} </ul>
                  </div>
              </td>
          </tr>
      </tfoot>--}}
      </table>
  @endif

@endcan
</div>
</div>
@endsection
