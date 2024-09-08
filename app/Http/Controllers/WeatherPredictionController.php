<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class WeatherPredictionController extends Controller
{
    // Function to show the prediction form
    public function index()
    {
        // Display the form without any predictions initially
        return view('weather.index');
    }

    // Function to handle the prediction request
    public function predict(Request $request)
    {
        // Initialize the variables to store predictions and dates
        $predictions = [];
        $dates = [];

        // Check if a CSV file is uploaded
        if ($request->hasFile('csv_file')) {
            // Validate the CSV file
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);

            // Process the CSV file
            $path = $request->file('csv_file')->getRealPath();
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0); // Assuming the CSV has headers

            $records = $csv->getRecords();
            $data = [];

            // Collect actual data and dates for the chart
            foreach ($records as $record) {
                $data[] = [
                    'Year' => $record['Year'],
                    'Month' => $record['Month'],
                    'Day' => $record['Day'],
                    'Min_Temp' => $record['Min_Temp'],
                    'Rainfall' => $record['Rainfall'],
                    'Humidity' => $record['Humidity'],
                    'Wind_Direction' => $record['Wind_Direction'],
                    'Wind_Speed' => $record['Wind_Speed'],
                    'timestamp' => $record['timestamp'],
                    'Latitude' => $record['Latitude'],
                    'Longitude' => $record['Longitude'],
                    'Cluster' => $record['Cluster'],
                ];

                // Prepare dates for X-axis in the chart
                $dates[] = $record['Year'] . '-' . $record['Month'] . '-' . $record['Day'];
            }

            // Log the data sent to the Flask API
            Log::info('Data sent to Flask API:', $data);

            // Send data to the Flask API
            $response = Http::post('http://127.0.0.1:5000/predict', ['features' => $data]);

            if ($response->successful()) {
                $predictionData = $response->json();
                Log::info('Flask API response:', $predictionData);
                $predictions = $predictionData['Predicted_Max_Temp'];
            } else {
                Log::error('Failed to get prediction from Flask API', ['response' => $response->body()]);
                return back()->withErrors(['msg' => 'Prediction API failed. Please try again.']);
            }
        }

        // Return the view with predictions and dates
        return view('weather.index', [
            'predictions' => $predictions,
            'dates' => $dates,
        ]);
    }
}
