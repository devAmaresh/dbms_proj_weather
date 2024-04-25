const GEO_API_URL = 'https://wft-geo-db.p.rapidapi.com/v1/geo';
const WEATHER_API_URL = 'https://api.openweathermap.org/data/2.5';
const WEATHER_API_KEY = 'b0198b1bd57098687a0d738b75fdfcff'; // Replace with your actual OpenWeatherMap API key
const GEO_API_OPTIONS = {
    method: 'GET',
    headers: {
        'X-RapidAPI-Key': '4f0dcce84bmshac9e329bd55fd14p17ec6fjsnff18c2e61917',
        'X-RapidAPI-Host': 'wft-geo-db.p.rapidapi.com',
    },
};

// Function to fetch weather data based on pincode
function fetchWeather(pincode) {
    const apiUrl = `${WEATHER_API_URL}/weather?zip=${pincode},IN&appid=${WEATHER_API_KEY}&units=metric`;

    // Fetch weather data from the API
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            // Update the UI with weather information
            document.getElementById('weather').innerHTML = `
                <div class="mt-4">
                    <h2 class="text-xl font-semibold mb-2">Current Weather</h2>
                    <p>Temperature: ${data.main.temp}°C</p>
                    <p>Weather: ${data.weather[0].description}</p>
                    <p>Humidity: ${data.main.humidity}%</p>
                    <p>Wind Speed: ${data.wind.speed} m/s</p>
                </div>
            `;
        })
        .catch(error => console.log('Error fetching weather data:', error));
}

