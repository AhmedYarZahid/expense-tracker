@extends('layouts.app')

@section('content')
    <div class="container">
        <h4><b>Expense Statistics</b></h4><br>

        <div>
            <label for="filter">Filter:</label>
            <select id="filter" onchange="updateStats()">
                <option value="all" selected>All</option>
                <option value="today">Today</option>
                <option value="last_week">Last Week</option>
                <option value="last_month">Last Month</option>
            </select>
        </div>

        <canvas id="expenseChart" width="400" height="200"></canvas>
    </div>

    <script>
        /**
         * Update stats
         */
        function updateStats() {
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
                })
                .catch(function (error) {
                    console.error('Error updating stats:', error);
                });
        }

        /**
         * Update chart
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

        updateStats();
    </script>
@endsection

