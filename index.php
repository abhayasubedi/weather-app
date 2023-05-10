<<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Weather Forecast</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <form method="get" action="">
  <input class="cityName" type="text" name="city" placeholder="Enter city name">
  <input class="submit" type="submit" name="submit" value="Search">
</form>
    <?php
  
  if (isset($_GET['submit'])) {
    $city = $_GET['city'];
  } else {
    $city = "Austin";
  }
  
  
  $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid=c6c79ee2a6045a8e9079b9155c776adb&units=metric";
  
  // Make API request and parse JSON response
  $response = file_get_contents($url);
  $data = json_decode($response, true);
  
  if (!$data) {
    // Handle API error
    die("Error: Failed to retrieve data from OpenWeatherMap API.");
  }
  
  // Extract relevant weather data
  $city_name = $data['name'];
  $condition = $data['weather'][0]['main'];
  $temperature = $data['main']['temp'];
  $pressure = $data['main']['pressure'];
  $humidity = $data['main']['humidity'];
  $wind_speed = $data['wind']['speed'];
  
  
  // Insert or update weather data in database
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $dbname = 'weather';
  
  $conn = mysqli_connect($host, $username, $password, $dbname);
  
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  
  // Check if data for the current hour is already present in database
  $sql = "SELECT * FROM weather_prototype2 WHERE `city`='$city_name' AND DATE(`date`) = CURDATE()";
  
  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) {
      // Update existing row with latest weather data
      $sql = "UPDATE weather_prototype2 SET `condition`='$condition', `temperature`='$temperature', `humidity`='$humidity', `wind_speed`='$wind_speed' WHERE `city`='$city_name' AND `date`= DATE_FORMAT(NOW(), '%Y-%m-%d %H:00:00')";
    } else {
    // Insert new row with current weather data
    
    $sql = "INSERT INTO weather_prototype2 (`city`, `date`, `condition`, `temperature`, `wind_speed`, `humidity`)
          VALUES ('$city_name', NOW(), '$condition', '$temperature','$wind_speed', '$humidity')";
  }
  
  
  mysqli_query($conn, $sql);
  
  // Retrieve latest weather data from database
  $sql = "SELECT * FROM weather_prototype2 WHERE `city`='$city_name' ORDER BY `date` DESC LIMIT 7";
  $result = mysqli_query($conn, $sql);
  
  echo "<table border='1'>";
  echo "<tr>";
  echo "<th>City</th>";
  echo "<th>Date/Time</th>";
  echo "<th>Condition</th>";
  echo "<th>Temperature</th>";
  echo "<th>Humidity</th>";
  echo "<th>Wind Speed</th>";
  echo "</tr>";
  while ($row = mysqli_fetch_assoc($result)) {

      $date = date('Y-m-d H:i:s', strtotime($row['date']));
      $condition = $row['condition'];
      $temperature = $row['temperature'];
    $humidity = $row['humidity'];
    $wind_speed = $row['wind_speed'];
    $city_name = $row['city'];

  
    echo "<tr>";
    echo "<td>{$city_name}</td>";
    echo "<td>{$date}</td>";
    echo "<td>{$condition}</td>";
    echo "<td>{$temperature}°C</td>";
    echo "<td>{$humidity}%</td>";
    echo "<td>{$wind_speed} m/s</td>";
    echo "</tr>";
}
echo "</table>";


// Close database connection
mysqli_close($conn);
?>

    </body>
</html>