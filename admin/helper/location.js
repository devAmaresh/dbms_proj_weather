// Initialize Leaflet map
let map = L.map('map').setView([20.5937, 78.9629], 4);

// Add OpenStreetMap tiles to the map
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

let marker;

// Add click event listener to map
map.on('click', function (e) {
    // Remove existing marker if present
    if (marker) {
        map.removeLayer(marker);
    }
    // Get coordinates of clicked location
    let lat = e.latlng.lat;
    let lng = e.latlng.lng;

    // Perform reverse geocoding to get the address
    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
        .then(response => response.json())
        .then(data => {
            // Extract the place name from the response
            let placeName = data.display_name;
            // Create marker and add to map at the clicked location
            marker = L.marker([lat, lng]).addTo(map);
            // Set the latitude and longitude values in the hidden input fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            // Set the location address in the <p> element
            document.getElementById('location_name').value = placeName;
        })
        .catch(error => {
            console.error('Error fetching reverse geocoding data:', error);
        });
});
