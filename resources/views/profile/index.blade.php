@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Your Profile</b></h4><br>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="post" action="{{ route('profile.update') }}" class="my-4">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="font-weight-bold">Name:</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email" class="font-weight-bold">Email:</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>

            <br>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
@endsection
