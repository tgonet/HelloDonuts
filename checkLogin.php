<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

// Reading inputs entered in previous page
$email = $_POST["email"];
$password = $_POST["password"];

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Define thge INSERT SQL statement
$qry = "SELECT ShopperID, Name, Email, Password FROM Shopper WHERE Email=?";

$stmt = $conn->prepare($qry);

// "ssssss" - 6 string parameters
$stmt->bind_param("s", $email);

// To Do 1 (Practical 2): Validate login credentials with database

if ($stmt->execute()) {  // SQL statement executed successfully

	$result = $stmt->get_result();
	$stmt->close();

	while ($row = $result->fetch_array()) {
		// Get the hashed password from database
		$hash_pwd = $row["Password"];
		
		// Verifies that a passowrd matches a hash
		if ((password_verify($password, $hash_pwd) == true) || ($password == $row["Password"])){
			// Save user's info in session variables
			$_SESSION["ShopperID"] = $row["ShopperID"];
			$_SESSION["ShopperName"] = $row["Name"];
			
			// To Do 2 (Practical 4): Get active shopping cart
			include_once("mysql_conn.php"); // Establish database connection handle: $conn
			$qry1 = "SELECT sc.ShopCartID, COUNT(sci.productid) AS NumOfRows FROM Shopcart sc LEFT JOIN ShopCartItem sci ON sci.ShopCartID=sc.ShopCartID WHERE ShopperID=? AND OrderPlaced=0";
			$stmt1 = $conn->prepare($qry1);
			$stmt1->bind_param("i", $_SESSION["ShopperID"]); //"i" - integer
			$stmt1->execute();
			$row = $stmt1->get_result()->fetch_array();
			$_SESSION["Cart"] = $row["ShopCartID"];
			$_SESSION["NumCartItem"] = $row["NumOfRows"];
			
			// Redirect to home page
			header("Location: index.php");
			exit;
		}
	}
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
	
}
else {
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}
	
// Include the Page Layout footer
include("footer.php");
?>