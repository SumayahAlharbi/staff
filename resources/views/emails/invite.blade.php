
@extends('emails.emaillayout')

@section('content')

<p>Dear,</p>

<p>You have invited to a new group</p>

<p><a href="{{ route('accept', $invite->token) }}">Click here</a> to join!</p>

@endsection
