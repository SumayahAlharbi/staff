
@extends('emails.emaillayout')

@section('content')

<p>Dear,</p>

<p>You have invited to a new group</p>

<p><a href="{{ route('accept', $invite->token) }}">Click here</a> to join!</p>
<p> Not working? </p>
 <p> 1- Visit: staff.ksauhs.com </p>
 <p> 2- Log in </p>
 <p> 3- Go to the Invitations Inbox </p>
 <p> 4- Accept the invitation </p>
 
@endsection
