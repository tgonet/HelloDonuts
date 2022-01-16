<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
$_SESSION["OrderID"] = 1;
$_SESSION["PaymentMethod"] = "PayPal";
if(isset($_SESSION["OrderID"])) {
    // Establish connection with SQL in this page
    include_once("mysql_conn.php"); 

    // Get order details
    $qry = "SELECT * FROM OrderData
				WHERE OrderID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["OrderID"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array();
    $stmt->close();

    echo "<div style='width:100%; margin:auto; text-align:center'>";

    // Headers
    echo "<h2 style='margin-top: 20px !important;'>Checkout Success!</h2>";
    echo "<h3 class='outsideBackground'>Your Order</h3>";

    // Blue container
    echo "<div class='background'>";

    // Company details
    echo "<div class='row'>";
    //Company logo
    echo "<div class='col-sm-6'>";
    echo "<img src='Images/hello_donut_logo.svg' alt='Logo' class='logo-img' style='width:80%'>";
    echo "</div>";
    //Company contact info and location
    echo "<div class='col-sm-6' style='width:80%;'>";
    echo "<p style='text-align:left;padding-left:20px;'>Address: 123 Donut Street 45 #06-12 S123456</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Tel: +65 91234567</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Email: hello@donut.com</p>";
    echo "</div>";
    echo "</div>";

    // Order details
    echo "<div class='row'>";
    //Column 1 - Customer details
    echo "<div class='col-sm-6'>";
    echo "<p style='text-align:left;padding-left:20px;'>Customer Name: $result[ShipName]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Address: $result[ShipAddress]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Tel: $result[ShipPhone]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Email: $result[ShipEmail]</p>";
    echo "</div>";
    //Column 2 - Delivery, payment, and order details
    echo "<div class='col-sm-6' style='width:80%;'>";
    echo "<p style='text-align:left;padding-left:20px;'>Order ID: $_SESSION[OrderID]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Date Ordered: $result[DateOrdered]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Payment Method: $_SESSION[PaymentMethod]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Delivery Date: $result[DeliveryDate]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Delivery Mode: $result[DeliveryMode]</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover'>";
    echo "<thead class= 'order-header'>";
    echo "<tr>";
    echo "<th>Donuts</th>";
    echo "<th>Unit Price (S$)</th>";
    echo "<th>Discount (S$)</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total (S$)</th>";
    echo "</tr>";   
    echo "</thead>";
    echo "<tbody>";

    // Get shopping cart items from orderdata result's "ShopCartID"
    $qry = "SELECT * FROM OrderData
            WHERE OrderID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["OrderID"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array();
    $stmt->close();
    // Display Cart Items from session variable "Items"
    /*
    foreach($_SESSION['Items'] as $key=>$item) {
        $name = $item["productName"];
        $price = $item["price"];
        $offeredPrice = $item["offeredPrice"];
        $quantity = $item["quantity"];
        if ($item["offeredPrice"] != NULL)
        {
            $discount = $item["price"] - $item["offeredprice"];
            $total = $quantity * $price;
        }
        else
        {
            $discount = "-";
            $total = $quantiy * $offeredPrice;
        }
        echo "<tr>";
        echo "<td><img id='donutImg' src='Images/Donuts/'. $productName. '.jpg'></br>
            $productName</td>";
        echo "<td style='vertical-align:middle'>$price</td>";
        
        echo "<td style='vertical-align:middle'>$discount</td>";
        echo "<td style='vertical-align:middle'>$quantity</td>";
        echo "<td style='vertical-align:middle; margin-bottom:10%'>$total</td>";
        echo "</tr>";
    }
    */
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
        echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
        echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
        echo '<a href="index.php">Continue shopping</a></p>';
} 

include("footer.php"); // Include the Page Layout footer
?>
