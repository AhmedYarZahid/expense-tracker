@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Your Profile</b></h4><br>
        <p><b>Name:</b> {{ $user->name }}</p>
        <p><b>Email:</b> {{ $user->email }}</p>
        <!-- Add more profile information as needed -->
    </div>
@endsection
