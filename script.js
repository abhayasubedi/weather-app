 //Student Name- Abhaya Subedi
 // ID- 2330521


// Getting all the html elements through id
const date = document.getElementById('date');
const temperature = document.getElementById('temp');
const pressure = document.getElementById('pressure');
const humidity = document.getElementById('humidity');
const windSpeed = document.getElementById('wind-speed');
const statusbar = document.getElementById('statusbar');
const city = document.getElementById('city');
const img = document.getElementById('des-img');
const windDirection = document.getElementById('wind-direction');
const day = document.getElementById("day");

const defaultCity = "Austin"
// URL for fethcing the JSON Data
const url = `https://api.openweathermap.org/data/2.5/weather?q=${defaultCity}&appid=5380671ae83d9f63c92d61fd69e9bc85&units=metric`


// Function to displayandget the data
function getDisplayData(url){

  // fetching the data from the url
  fetch(url) // returns the promise
  .then(response => response.json()) // Returns the promise
  .then(data => {
  

    
    // Updating the data in the web page for each elements
    temperature.innerHTML = `${Math.ceil(data.main.temp)}&#176C`;
    pressure.innerHTML = `Pressure: ${Math.ceil(data.main.pressure)}hpa`;
    humidity.innerHTML = `Humidity: ${Math.floor(data.main.humidity)}%`;
    windSpeed.innerHTML = `Wind Speed: ${(data.wind.speed)}m/s`;
    statusbar.innerHTML = `${data.weather[0].description}`;
    // changing the icon img src to update the icon according to status
    img.src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
    windDirection.innerHTML = `Wind Direction: ${data.wind.deg}&deg`;
    // Adding thhe date and day of the local time in city
    date.innerHTML=`${new Date(data.dt * 1000).toLocaleString('default', { month: 'long', day: 'numeric',year: 'numeric' })}`;
    day.innerHTML = `${new Date(data.dt * 1000).toLocaleString('default',{ weekday: 'long',})}`
    document.getElementById("city").textContent = data.name;
  });

}

// passing the url and calling the function
getDisplayData(url);


document.getElementById("search").addEventListener("keydown",e=>{
  switch(e.keyCode){
    case 13:
      getDisplayData(`https://api.openweathermap.org/data/2.5/weather?q=${e.target.value}&appid=5380671ae83d9f63c92d61fd69e9bc85&units=metric`);
      break;
  }
});


