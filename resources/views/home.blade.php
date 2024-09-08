@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-600 via-purple-500 to-pink-500 overflow-hidden relative">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="cloud left-1/4 top-1/4"></div>
        <div class="cloud left-3/4 top-1/2"></div>
        <div class="sun right-1/4 bottom-1/4"></div>
    </div>
    
    <div class="text-center z-10">
        <h1 class="text-6xl font-bold text-white mb-4 animate-fade-in-down drop-shadow-lg">
            Weather Prediction 
        </h1>
        <p class="text-2xl text-white mb-8 animate-fade-in-up max-w-2xl mx-auto">
            Harnessing the power of advanced machine learning for precise weather forecasts
        </p>
        <a href="{{ route('weather.index') }}" class="inline-block bg-white text-blue-600 font-bold py-4 px-8 rounded-full shadow-lg hover:bg-blue-100 transform hover:scale-105 transition duration-300 animate-pulse text-xl">
            Start Predicting
        </a>
    </div>
   
    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 animate-fade-in max-w-6xl w-full px-4">
        <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
            <h3 class="text-2xl font-semibold text-white mb-4">Advanced AI</h3>
            <p class="text-gray-100">Leveraging state-of-the-art machine learning algorithms for unparalleled prediction accuracy.</p>
        </div>
        <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
            <h3 class="text-2xl font-semibold text-white mb-4">Real-time Data</h3>
            <p class="text-gray-100">Continuously updated with the latest meteorological data for up-to-the-minute forecasts.</p>
        </div>
        <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
            <h3 class="text-2xl font-semibold text-white mb-4">User-Friendly</h3>
            <p class="text-gray-100">Intuitive interface designed for seamless and effortless weather forecasting experience.</p>
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .cloud {
        position: absolute;
        width: 200px;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        animation: float 6s ease-in-out infinite;
    }
    
    .cloud::before,
    .cloud::after {
        content: '';
        position: absolute;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
    }
    
    .cloud::before {
        width: 100px;
        height: 100px;
        top: -50px;
        left: 10px;
    }
    
    .cloud::after {
        width: 120px;
        height: 120px;
        top: -70px;
        right: 10px;
    }
    
    .sun {
        position: absolute;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255,255,0,0.8) 0%, rgba(255,165,0,0.6) 70%);
        border-radius: 50%;
        box-shadow: 0 0 50px rgba(255,255,0,0.3);
        animation: float 8s ease-in-out infinite;
    }
    
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fade-in {
        0% { opacity: 0; }
        100% { opacity: 1; }
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
@endsection