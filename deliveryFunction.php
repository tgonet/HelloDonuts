<?php 
session_start();
date_default_timezone_set('Asia/Singapore');

if (isset($_POST['delivery_mode']) && isset($_POST['delivery_time'])) {
    $_SESSION["DeliveryDate"] = new DateTime('now');
    $_SESSION["DeliveryDiscount"] = 0;
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
    $_SESSION["DeliveryDate"]->modify('+1 day');
    if ($_SESSION["SubTotal"] > 50) {
        $_SESSION["WaiveDelivery"] = true;
        $_SESSION["DeliveryDiscount"] = -$_SESSION["DeliveryCharge"];
    } else {
        $_SESSION["WaiveDelivery"] = false;
    }
    selectedTime();
    formatNumbers();
    header("Location: reviewOrder.php");
	exit;
}

function expressMode() {
    $_SESSION["DeliveryMode"] = 'Express';
    $_SESSION["DeliveryCharge"] = 5;
    $_SESSION["WaiveDelivery"] = false;
    $timenow = date("H:i:s");

    if ($timenow < '09:00:00' || ($timenow >= '09:00:00') && ($timenow <= '12:00:00'))
    {
        $_SESSION['DeliveryTime'] = "9 am - 12 pm";
        $_SESSION["DeliveryDate"]->modify('+2 hour');
    }
    else if (($timenow > '12:00:00') && ($timenow <= '15:00:00'))
    {
        $_SESSION['DeliveryTime'] = "12 pm - 3 pm";
        $_SESSION["DeliveryDate"]->modify('+2 hour');
    }
    else if (($timenow > '15:00:00') && ($timenow <= '18:00:00'))
    {
        $_SESSION['DeliveryTime'] = "3 pm - 6 pm";
        $_SESSION["DeliveryDate"]->modify('+2 hour');

    }
    else{
        $_SESSION['DeliveryTime'] = "9 am - 12 pm";
        $_SESSION["DeliveryDate"]->modify('+1 day');
    }
    formatNumbers();
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

function formatNumbers(){
    $_SESSION["DeliveryCharge"] = number_format($_SESSION["DeliveryCharge"],2);
    $_SESSION["DeliveryDiscount"] = number_format($_SESSION["DeliveryDiscount"],2);
    $_SESSION["DeliveryDate"] = $_SESSION["DeliveryDate"]->format('Y-m-d');
}
?>