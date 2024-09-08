$(document).ready(function() {
    let chart;

    $('#predictionForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/predict',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#result').html(`
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                        <p class="font-bold">Prediction Results:</p>
                        <p>Random Forest: ${response.random_forest_prediction}</p>
                        <p>Gradient Boosting: ${response.gradient_boosting_prediction}</p>
                    </div>
                `);
                updateChart(response);
            },
            error: function(xhr) {
                $('#result').html(`
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                        <p class="font-bold">Error</p>
                        <p>An error occurred. Please try again.</p>
                    </div>
                `);
            }
        });
    });

    function updateChart(data) {
        if (chart) {
            chart.destroy();
        }

        const ctx = document.getElementById('predictionChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Random Forest', 'Gradient Boosting'],
                datasets: [{
                    label: 'Predictions',
                    data: [data.random_forest_prediction, data.gradient_boosting_prediction],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(239, 68, 68, 0.5)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});