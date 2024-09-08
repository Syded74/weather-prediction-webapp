<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Prediction App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fade-in {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        .animate-fade-in-down {
            animation: fade-in-down 1s ease-out;
        }
        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
        }
        .animate-fade-in {
            animation: fade-in 1.5s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <main>
        @yield('content')
    </main>
 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</body>
</html>