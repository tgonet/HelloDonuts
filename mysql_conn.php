<?php
//Connection Parameters
$servername = 'localhost';
$username = 'root';
$userpwd = '07G48u00!';
$dbname = 'hellodonuts'; 

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname);
echo($conn->connect_error);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);	
}
?>
