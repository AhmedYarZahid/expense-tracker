@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add Expense</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('expenses.store') }}">
            @csrf
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="expense_type">Expense Type</label>
                <select class="form-control" id="expense_type" name="expense_type" required>
                    <option value="in">In</option>
                    <option value="out">Out</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Add Expense</button>
        </form>
    </div>
@endsection
