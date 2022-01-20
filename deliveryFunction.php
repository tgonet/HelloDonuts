<?php 
session_start();
if (isset($_POST['action'])) {
 	switch ($_POST['action']) {
    	case 'mode':
        	selectMode();
            break;
    }
}

function selectMode() {
    // Check if user logged in 
	if (! isset($_SESSION["ShopperID"])) {
		// redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}

    $mode = $_POST['delivery_mode'];
    // TODO: idk

    header("Location: reviewOrder.php");
    exit;
}
?>