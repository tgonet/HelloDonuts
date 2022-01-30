<?php 
session_start();
if (isset($_POST['delivery_mode']) && isset($_POST['delivery_time'])) {
 	switch ($_POST['delivery_mode']) {
    	case 'Normal':
        	normalMode();
            break;
        case 'Express':
            expressMode();
            break;
    }
}

function normalMode() {
    $_SESSION["DeliveryMode"] = 'Normal';
    $_SESSION["DeliveryCharge"] = 2;
    if ($_SESSION["SubTotal"] > 50) {
        $_SESSION["WaiveDelivery"] = true;
    } else {
        $_SESSION["WaiveDelivery"] = false;
    }
    selectedTime();
    header("Location: reviewOrder.php");
	exit;
}

function expressMode() {
    $_SESSION["DeliveryMode"] = 'Express';
    $_SESSION["DeliveryCharge"] = 5;
    $_SESSION["WaiveDelivery"] = false;
    selectedTime();
    header("Location: reviewOrder.php");
	exit;
}

function selectedTime() {
    switch ($_POST['delivery_time']) {
        case '9to12':
            $_SESSION['DeliveryTime'] = "9 am - 12 pm";
            break;
        case '12to3':
            $_SESSION['DeliveryTime'] = "12 pm - 3 pm";
            break;
        case '3to6':
            $_SESSION['DeliveryTime'] = "3 pm - 6 pm";
            break;
    }
}
?>