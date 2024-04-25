// Function to fetch weather data based on pincode
function fetchWeather(pincode) {
 // Replace 'YOUR_API_KEY' with your actual OpenWeatherMap API key
 const apiKey = 'b0198b1bd57098687a0d738b75fdfcff';
 const apiUrl = `https://api.openweathermap.org/data/2.5/weather?zip=${pincode},IN&appid=${apiKey}&units=metric`;

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
