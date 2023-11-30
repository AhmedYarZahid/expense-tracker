@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Edit Category</b></h4><br>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('categories.update', $category->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
@endsection
