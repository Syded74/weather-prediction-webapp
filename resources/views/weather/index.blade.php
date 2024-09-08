@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-500 to-pink-500 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-6 text-center animate-fade-in-down">Enhanced Weather Prediction Dashboard</h1>

        <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl mb-6 animate-fade-in-up">
            <h2 class="text-xl font-bold text-white mb-3">Upload CSV for Weather Prediction</h2>
            <form action="{{ route('predict') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center">
                @csrf
                <input type="file" name="csv_file" id="csv_file" class="flex-grow p-2 border border-white bg-white bg-opacity-20 text-white rounded-lg sm:rounded-r-none mb-2 sm:mb-0">
                <button type="submit" class="w-full sm:w-auto bg-white text-blue-600 font-bold py-2 px-4 rounded-lg sm:rounded-l-none hover:bg-blue-100 transition duration-300">
                    Predict from CSV
                </button>
            </form>
        </div>

        <a href="{{ route('home') }}" class="inline-block bg-white text-blue-600 font-bold py-2 px-4 rounded-lg hover:bg-blue-100 transition duration-300 mb-6 animate-fade-in">
            Back to Home
        </a>

        @if (isset($predictions) && !empty($predictions))
            <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl mb-6 animate-fade-in">
                <h3 class="text-lg font-bold text-white mb-2">Predicted Maximum Temperatures</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-white">
                        <thead>
                            <tr>
                                @foreach($predictions as $index => $temp)
                                    <th class="px-2 py-1 text-left">Day {{ $index + 1 }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($predictions as $temp)
                                    <td class="px-2 py-1">{{ number_format($temp, 2) }}°C</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl animate-fade-in">
                    <h3 class="text-lg font-bold text-white mb-2">Temperature Trend</h3>
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="temperatureChart"></canvas>
                    </div>
                    <p class="mt-2 text-sm text-white">
                        This graph shows the predicted maximum temperature for each day. The trend can help identify patterns or anomalies in temperature changes over time.
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl animate-fade-in">
                    <h3 class="text-lg font-bold text-white mb-2">Temperature Distribution</h3>
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="distributionChart"></canvas>
                    </div>
                    <p class="mt-2 text-sm text-white">
                        This chart displays the distribution of predicted temperatures. Each bar represents a 0.1°C range and shows how many days fall within that range, helping to understand the overall temperature pattern.
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl animate-fade-in">
                    <h3 class="text-lg font-bold text-white mb-2">Weekly Average Temperature</h3>
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="weeklyAverageChart"></canvas>
                    </div>
                    <p class="mt-2 text-sm text-white">
                        This chart shows the average temperature for each week. It helps to identify broader temperature trends and compare weeks to each other.
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl animate-fade-in">
                    <h3 class="text-lg font-bold text-white mb-2">Daily Temperature Comparison</h3>
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="dailyComparisonChart"></canvas>
                    </div>
                    <p class="mt-2 text-sm text-white">
                        This chart compares the predicted maximum temperature for each day with the overall average, helping to identify days that are significantly warmer or cooler than usual.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dates = @json($dates ?? []);
    const predictions = @json($predictions ?? []);

    if (dates.length > 0 && predictions.length > 0) {
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: 'white', font: { size: 12 } }
                },
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: Math.min(...predictions),
                            yMax: Math.min(...predictions),
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 2,
                        },
                        line2: {
                            type: 'line',
                            yMin: Math.max(...predictions),
                            yMax: Math.max(...predictions),
                            borderColor: 'rgb(75, 192, 192)',
                            borderWidth: 2,
                        }
                    }
                }
            },
            scales: {
                x: { 
                    ticks: { color: 'white', font: { size: 10 } },
                    grid: { color: 'rgba(255, 255, 255, 0.1)' }
                },
                y: { 
                    ticks: { color: 'white', font: { size: 10 } },
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    beginAtZero: false
                }
            }
        };

        // Temperature Trend Chart
        new Chart(document.getElementById('temperatureChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Predicted Max Temp (°C)',
                    data: predictions,
                    borderColor: 'rgba(255, 255, 255, 1)',
                    backgroundColor: 'rgba(255, 255, 255, 0.2)',
                    fill: true,
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    y: {
                        ...chartOptions.scales.y,
                        ticks: {
                            ...chartOptions.scales.y.ticks,
                            callback: function(value) {
                                return value.toFixed(2) + '°C';
                            }
                        }
                    }
                }
            }
        });

        // Temperature Distribution Chart
        const distributionData = predictions.reduce((acc, temp) => {
            const range = (Math.floor(temp * 10) / 10).toFixed(1);
            acc[range] = (acc[range] || 0) + 1;
            return acc;
        }, {});

        new Chart(document.getElementById('distributionChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: Object.keys(distributionData).map(range => `${range}°C`),
                datasets: [{
                    label: 'Number of Days',
                    data: Object.values(distributionData),
                    backgroundColor: 'rgba(255, 255, 255, 0.6)',
                    borderColor: 'rgba(255, 255, 255, 1)',
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    y: {
                        ...chartOptions.scales.y,
                        ticks: {
                            ...chartOptions.scales.y.ticks,
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Weekly Average Chart
        const weeklyData = dates.reduce((acc, date, index) => {
            const week = Math.floor(index / 7);
            if (!acc[week]) {
                acc[week] = { total: 0, count: 0 };
            }
            acc[week].total += predictions[index];
            acc[week].count += 1;
            return acc;
        }, []);

        const weeklyAverages = weeklyData.map((week, index) => ({
            week: `Week ${index + 1}`,
            average: week.total / week.count
        }));

        new Chart(document.getElementById('weeklyAverageChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: weeklyAverages.map(w => w.week),
                datasets: [{
                    label: 'Weekly Average Temperature (°C)',
                    data: weeklyAverages.map(w => w.average),
                    backgroundColor: 'rgba(255, 255, 255, 0.6)',
                    borderColor: 'rgba(255, 255, 255, 1)',
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    y: {
                        ...chartOptions.scales.y,
                        ticks: {
                            ...chartOptions.scales.y.ticks,
                            callback: function(value) {
                                return value.toFixed(2) + '°C';
                            }
                        }
                    }
                }
            }
        });

        // Daily Temperature Comparison Chart
        const averageTemp = predictions.reduce((sum, temp) => sum + temp, 0) / predictions.length;
        
        new Chart(document.getElementById('dailyComparisonChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Max Temp (°C)',
                    data: predictions,
                    backgroundColor: predictions.map(temp => 
                        temp > averageTemp ? 'rgba(255, 99, 132, 0.6)' : 'rgba(54, 162, 235, 0.6)'
                    ),
                    borderColor: 'rgba(255, 255, 255, 1)',
                }, {
                    label: 'Average Temp',
                    data: new Array(dates.length).fill(averageTemp),
                    type: 'line',
                    borderColor: 'rgba(255, 255, 0, 1)',
                    borderWidth: 2,
                    fill: false,
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    ...chartOptions.scales,
                    y: {
                        ...chartOptions.scales.y,
                        ticks: {
                            ...chartOptions.scales.y.ticks,
                            callback: function(value) {
                                return value.toFixed(2) + '°C';
                            }
                        }
                    }
                },
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toFixed(2) + '°C';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush