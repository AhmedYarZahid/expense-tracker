@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Expense Statistics</b></h4><br>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div>
            <label for="filter">Filter:</label>
            <select id="filter" onchange="updateDashboard()">
                <option value="all" selected>All</option>
                <option value="today">Today</option>
                <option value="last_week">Last Week</option>
                <option value="last_month">Last Month</option>
            </select>
        </div>

        <canvas id="expenseChart" width="400" height="200"></canvas>

        <br>

        <table class="table" id="expensesTable">
            <thead>
            <tr>
                <th>Category</th>
                <th>Expense Type</th>
                <th>Amount</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        /**
         * Update dashboard.
         */
        function updateDashboard() {
            var filter = document.getElementById('filter').value;

            // Make an AJAX request to the backend to get updated stats
            axios.get('/dashboard/expense-stats', {
                params: {
                    filter: filter
                }
            })
                .then(function (response) {
                    // Update the chart with the new data
                    updateChart(response.data);

                    // Update the expenses list
                    updateExpensesList(response.data);
                })
                .catch(function (error) {
                    console.error('Error updating stats:', error);
                });
        }

        /**
         * Update chart.
         *
         * @param data
         */
        function updateChart(data) {
            var ctx = document.getElementById('expenseChart').getContext('2d');

            // Check if a chart already exists on the canvas
            if (window.myChart) {
                // Destroy the existing chart
                window.myChart.destroy();
            }

            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total In Expenses', 'Total Out Expenses'],
                    datasets: [{
                        label: 'Expense Statistics',
                        data: [data.totalInExpenses, data.totalOutExpenses],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.parsed.y;
                                }
                            }
                        }
                    }
                }
            });
        }

        /**
         * Update expenses list.
         *
         * @param data
         */
        function updateExpensesList(data) {
            var expensesTable = document.getElementById('expensesTable');
            var expensesRows = '';

            data.expenses.forEach(function(expense) {
                expensesRows += '<tr>';
                expensesRows += '<td>' + expense.category.name + '</td>';
                expensesRows += '<td>' + expense.expense_type + '</td>';
                expensesRows += '<td>$' + expense.amount + '</td>';
                expensesRows += '<td>' + expense.created_at.replace("T", " ").replace(".000000Z", "") + '</td>';
                expensesRows += '<td>';
                expensesRows += '<a href="/expenses/' + expense.id + '/edit" class="btn btn-warning">Edit</a>&nbsp';
                expensesRows += '<form action="/expenses/' + expense.id + '" method="post" style="display:inline;">';
                expensesRows += '@csrf';
                expensesRows += '@method("DELETE")';
                expensesRows += '<button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                expensesRows += '</form>';
                expensesRows += '</td>';
                expensesRows += '</tr>';
            });

            // Replace the content of the tbody with the updated expenses rows
            expensesTable.querySelector('tbody').innerHTML = expensesRows;
        }

        updateDashboard();
    </script>
@endsection

