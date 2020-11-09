@extends('layouts.app')
@section('title', 'Absent Sheet')
@section('content')

<div class = 'container'>

{{-- <button type="button" class="btn btn-link"></button> --}}

<div class="card uper">
  <div class="card-header">
   Absent Sheet

  </div>
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

 
  <!--<div class="float-left"> <a class="btn btn-primary" href="{{ route('attendancesheet.export') }}" role="button">Export</a> </div>-->
  <div class="card">
    <div class="card-body">
  <form method="post" action="{{ route('absent') }}">
    <div class="row">
      <div class="form-group col-md-6">
          @csrf
          <label for="date">Date</label>
          <input type="date"  class="form-control" name="date" />
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="type">Type</label>
          <select class="form-control" name="type">
              <option value="Check In">Check In</option>
              <option value="Check Out">Check Out</option>
          </select>
        </div>
      </div>

      <!-- <div class="col-md-4">
        <div class="form-group">
          <label for="sort_by">Sort by</label>
          <select class="form-control" name="sort_by">
              <option value="group">Group</option>
          </select>
        </div>
      </div>
    </div> -->
    <div class="col-md-4">
    <button type="submit" class="btn btn-primary">View</button>
    </div>
  </form>
</div>
</div>
  
  <div>
<table class="table table-striped">
    <thead>
        <tr>
          <td>Name</td>
          <td>Email</td>
          <td>Group</td>
          <td>Type</td>
          <td>Date</td>
        </tr>
        <tr>
    <td>
</td>
</tr>
    </thead>
    <tbody>
   
    @foreach($absentSheets as $absentSheet)
    <tr>
      <td>{{$absentSheet->name}}</td>
      <td>{{$absentSheet->email}}</td>
        <td>   
          @if(!empty($absentSheet->group))
            @foreach($absentSheet->group as $userGroup)
               {{$userGroup->group_name}} <br>
            @endforeach
            @else
            No Groups
          @endif
        </td>
      <td>@if($type)
      No {{$type}}
      @endif
      </td>
      <td>{{$date}}</td>
        </tr>
    @endforeach

  </tbody>
  <tfoot>
      <tr>
          <td colspan="6">
              <div class="text-right">
                  <ul>   {{ $absentSheets->links() }} </ul>
              </div>
          </td>
      </tr>
  </tfoot>
  </table>
    </div>  

</div>
</div>
@endsection
