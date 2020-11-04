<div wire:poll>
<table class="table table-striped">
    <thead>
        <tr>
          <td>Name</td>
          <td>Email</td>
          <td>Group</td>
          <td>Type</td>
          <td>Date/Time</td>
          <td>GPS</td>
        </tr>
    </thead>
    <tbody>
    @foreach($attendancesheets as $attendancesheet)
    <tr>
      <td>{{$attendancesheet->user->name}}</td>
      <td>{{$attendancesheet->user->email}}</td>
      <td>{{$attendancesheet->group['group_name']}}</td>
      <td>{{$attendancesheet->action}}</td>
      <td>{{$attendancesheet['created_at']}} <b>({{$attendancesheet['created_at']->diffForHumans()}})</b></td>
      <td><a target="_blank" href="https://www.google.com/search?q={{$attendancesheet->coords}}">Map</a></td>
  </tr>
    @endforeach

  </tbody>
  <tfoot>
      <tr>
          <td colspan="6">
              <div class="text-right">
                  <ul>   {{ $attendancesheets->links() }} </ul>
              </div>
          </td>
      </tr>
  </tfoot>
  </table>
    </div>