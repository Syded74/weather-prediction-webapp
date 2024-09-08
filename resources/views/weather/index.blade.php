@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-500 to-pink-500 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-6 text-center">Enhanced Weather Prediction Dashboard</h1>

        <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl mb-6">
            <h2 class="text-xl font-bold text-white mb-3">Upload CSV for Weather Prediction</h2>
            <form action="{{ route('predict') }}" method="POST" enctype="multipart/form-data" class="flex items-center">
                @csrf
                <input type="file" name="csv_file" id="csv_file" class="flex-grow p-2 border border-white bg-white bg-opacity-20 text-white rounded-l-lg">
                <button type="submit" class="bg-white text-blue-600 font-bold py-2 px-4 rounded-r-lg hover:bg-blue-100 transition duration-300">
                    Predict from CSV
                </button>
            </form>
        </div>

        @if (isset($predictions) && !empty($predictions))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl col-span-2">
                    <h3 class="text-lg font-bold text-white mb-2">Temperature Trend</h3>
                    <canvas id="temperatureChart" class="w-full" height="300"></canvas>
                    <p class="mt-2 text-sm text-white">
                        This graph shows the predicted maximum temperature for each day. The trend can help identify patterns or anomalies in temperature changes over time.
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-2">Temperature Distribution</h3>
                    <canvas id="distributionChart" class="w-full" height="300"></canvas>
                    <p class="mt-2 text-sm text-white">
                        This chart displays the distribution of predicted temperatures. Each bar represents a 5°C range and shows how many days fall within that range, helping to understand the overall temperature pattern.
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-4 rounded-xl shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-2">Weekly Average Temperature</h3>
                    <canvas id="weeklyAverageChart" class="w-full" height="300"></canvas>
                    <p class="mt-2 text-sm text-white">
                        This chart shows the average temperature for each week. It helps to identify broader temperature trends and compare weeks to each other.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                }
            },
            scales: {
                x: { 
                    ticks: { color: 'white', font: { size: 10 } },
                    grid: { color: 'rgba(255, 255, 255, 0.1)' }
                },
                y: { 
                    ticks: { color: 'white', font: { size: 10 } },
                    grid: { color: 'rgba(255, 255, 255, 0.1)' }
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
            options: chartOptions
        });

        // Temperature Distribution Chart
        const distributionData = predictions.reduce((acc, temp) => {
            const range = Math.floor(temp / 5) * 5;
            acc[range] = (acc[range] || 0) + 1;
            return acc;
        }, {});

        new Chart(document.getElementById('distributionChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: Object.keys(distributionData).map(range => `${range}-${parseInt(range) + 5}°C`),
                datasets: [{
                    label: 'Temperature Distribution',
                    data: Object.values(distributionData),
                    backgroundColor: 'rgba(255, 255, 255, 0.6)',
                    borderColor: 'rgba(255, 255, 255, 1)',
                }]
            },
            options: chartOptions
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
            options: chartOptions
        });
    }
});
</script>
@endpush