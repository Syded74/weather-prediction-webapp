@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Predicted Maximum Temperature</h2>
        <canvas id="predictionChart"></canvas>
        <script>
            const ctx = document.getElementById('predictionChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates), // Dates for X-axis
                    datasets: [{
                        label: 'Predicted Max Temp (°C)',
                        data: @json($predictions), // Prediction data
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        x: { title: { display: true, text: 'Dates' } },
                        y: { title: { display: true, text: 'Temperature (°C)' } }
                    }
                }
            });
        </script>
    </div>
@endsection
