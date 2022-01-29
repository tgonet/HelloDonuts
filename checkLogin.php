<?php
	// Detect the current session
	session_start();

	// Reading inputs entered in previous page
	$email = $_POST["email"];
	$password = $_POST["password"];

	include_once("mysql_conn.php");

	$qry = "SELECT ShopperID, Name, Email, Password FROM Shopper WHERE Email=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $email);

	if ($stmt->execute()) {  

		$result = $stmt->get_result();
		$stmt->close();

		while ($row = $result->fetch_array()) {
			// Get the hashed password from database
			$hash_pwd = $row["Password"];
			if ((password_verify($password, $hash_pwd) == true) || ($password == $hash_pwd)){
				// Save user's info in session variables
				$_SESSION["ShopperID"] = $row["ShopperID"];
				$_SESSION["ShopperName"] = $row["Name"];
				
				include_once("mysql_conn.php"); 
				$qry1 = "SELECT sc.ShopCartID, COUNT(sci.productid) AS NumOfRows FROM Shopcart sc LEFT JOIN ShopCartItem sci ON sci.ShopCartID=sc.ShopCartID WHERE ShopperID=? AND OrderPlaced=0";
				$stmt1 = $conn->prepare($qry1);
				$stmt1->bind_param("i", $_SESSION["ShopperID"]); 
				$stmt1->execute();
				$row = $stmt1->get_result()->fetch_array();
				$_SESSION["Cart"] = $row["ShopCartID"];
				$_SESSION["NumCartItem"] = $row["NumOfRows"];
				echo "Login Successful";
			}
		}
		echo "Your email or password is incorrect.";
		
	}
	else {
		echo "Your email or password is incorrect.";
	}
?>