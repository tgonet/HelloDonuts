<?php
include_once("cartFunctions.php");
include("header.php");

// if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
// 	// redirect to login page if the session variable shopperid is not set
// 	header ("Location: login.php");
// 	exit;
// }

echo "<h1 class='page-title'>Delivery Mode</h1>";

include("footer.php");
?>