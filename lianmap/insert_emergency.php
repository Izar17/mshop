<?php
$servername = "localhost"; // Change this to your database server
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "mshop_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the AJAX request
$name = $_POST['name'];
$contact = $_POST['contact'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$status = $_POST['status'];
$shopId = $_POST['shopId'];
$customerId = $_POST['customerId'];

// Insert into emergency table
$sql = "INSERT INTO emergency (name, contact, lat, lng, status, vendor_id, customer_id) VALUES ('$name', '$contact', '$lat', '$lng', '$status', '$shopId', '$customerId')";

if ($conn->query($sql) === TRUE) {
    echo "New emergency request recorded successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>