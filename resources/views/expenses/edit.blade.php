@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Edit Expense</b></h4><br>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('expenses.update', $expense->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $expense->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="expense_type">Expense Type</label>
                <select class="form-control" id="expense_type" name="expense_type" required>
                    <option value="in" {{ $expense->expense_type == 'in' ? 'selected' : '' }}>In</option>
                    <option value="out" {{ $expense->expense_type == 'out' ? 'selected' : '' }}>Out</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" value="{{ $expense->amount }}" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Update Expense</button>
        </form>
    </div>
@endsection
