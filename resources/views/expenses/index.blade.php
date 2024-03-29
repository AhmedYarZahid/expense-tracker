@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Expenses</b></h4><br>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3">Add Expense</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>Category</th>
                <th>Expense Type</th>
                <th>Amount</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->category->name }}</td>
                    <td>{{ $expense->expense_type }}</td>
                    <td>${{ $expense->amount }}</td>
                    <td>{{ $expense->created_at }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="post" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
