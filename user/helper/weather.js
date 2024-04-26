const GEO_API_URL = "https://wft-geo-db.p.rapidapi.com/v1/geo";
const WEATHER_API_URL = "https://api.openweathermap.org/data/2.5";
const WEATHER_API_KEY = ""; // Replace with your actual OpenWeatherMap API key
const GEO_API_OPTIONS = {
  method: "GET",
  headers: {
    "X-RapidAPI-Key": "4f0dcce84bmshac9e329bd55fd14p17ec6fjsnff18c2e61917",
    "X-RapidAPI-Host": "wft-geo-db.p.rapidapi.com",
  },
};

// Function to fetch weather data based on pincode
function fetchWeather(pincode) {
  const apiUrl = `${WEATHER_API_URL}/weather?zip=${pincode},IN&appid=${WEATHER_API_KEY}&units=metric`;

  // Fetch weather data from the API
  fetch(apiUrl)
    .then((response) => response.json())
    .then((data) => {
      // Update the UI with weather information
      const weatherIcon = getWeatherIcon(data.weather[0].main);
      console.log(data);
      const weatherCard = document.getElementById("weather");
      document.getElementById("weather").innerHTML = `
                <div class="mt-4">
                 <div class="border-2 border-black rounded-md p-5 bg-zinc-100 hover:bg-zinc-200">
                <h2 class="text-xl font-semibold mb-2 text-center underline underline-offset-1">Current Weather</h2>
                <div class="my-2"><i class="fas fa-thermometer-three-quarters text-yellow-400"></i> Temperature: ${data.main.temp}°C</div>
                <div class="my-2"><i class="fas fa-temperature-high text-yellow-500"></i> Feels Like: ${data.main.feels_like}°C</div>
                <div class="my-2"><i class="fas fa-cloud-sun text-yellow-600"></i> Weather: ${data.weather[0].description}</div>
                <div class="my-2"><i class="fas fa-tint text-blue-500"></i> Humidity: ${data.main.humidity}%</div>
                <div class="my-2"><i class="fas fa-wind text-blue-300"></i> Wind Speed: ${data.wind.speed} m/s</div>
        
                 <div class="text-center mt-2"><i class="fas ${weatherIcon}"></i></div>
                
                    </div>
                </div>
            `;
    })
    .catch((error) => console.log("Error fetching weather data:", error));
}

// Function to get weather icon based on weather condition
function getWeatherIcon(weatherCondition) {
  switch (weatherCondition.toLowerCase()) {
    case "clear":
      return "fa-sun";
    case "clouds":
      return "fa-cloud";
    case "rain":
      return "fa-cloud-showers-heavy";
    default:
      return "fa-question-circle"; // Default icon for unknown weather condition
  }
}
