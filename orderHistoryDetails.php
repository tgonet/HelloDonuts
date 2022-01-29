<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if (!isset($_GET["orderid"]))
{
    header("Location: orderHistory.php");
    exit;
}
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

<?php
$orderId=$_GET["orderid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
// Get order details
$qry = "SELECT OrderID, ShipName, ShipAddress, ShipPhone, ShipEmail, DateOrdered, DeliveryDate, DeliveryTime, DeliveryMode,Message, ShopCartID
        FROM OrderData
        WHERE OrderID = ?";
/*$qry = "SELECT o.OrderID, o.ShipName, o.ShipAddress, o.ShipPhone, o.ShipEmail, o.DateOrdered, o.DeliveryDate, o.DeliveryTime, o.DeliveryMode 
        FROM OrderData o INNER JOIN Shopcart sc on o.ShopCartID = sc.ShopCartID
        WHERE o.ShopCartID = ?";
        */
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$orderMessage = $result["Message"];
$shopCartID = $result["ShopCartID"];
$deliveryMode = $result["DeliveryMode"];
$stmt->close();

echo "<div class='row' style='padding:20px 0 0 20px'>";
echo "<div class='col-sm-12'>";
echo "<a class='back-link' href='orderHistory.php'>";
echo "<i class='fas fa-chevron-left back-btn'></i> Back to Order History";
echo "</a>";
echo "</div>";
echo "</div>";

echo "<div style='width:100%; margin:auto; text-align:center'>";

// Headers
echo "<h3 class='outsideBackground'style='margin-top: 20px !important;'>Order $orderId</h3>";

// Blue container
echo "<div class='background' id= 'customerOrder'>";

// Order details
echo "<div class='row customer-details' style='padding-top:20px;'>";
//Column 1 - Customer details and order id
echo "<div class='col-sm-6'>";
echo "<p style='text-align:left;padding-left:20px;'>Order ID: $orderId</p>";
echo "<p style='text-align:left;padding-left:20px;'>Customer Name: $result[ShipName]</p>";
echo "<p style='text-align:left;padding-left:20px;'>Address: $result[ShipAddress]</p>";
echo "<p style='text-align:left;padding-left:20px;'>Email: $result[ShipEmail]</p>";
echo "</div>";
//Column 2 - Delivery, payment, and order details
echo "<div class='col-sm-6' style='width:80%;'>";
echo "<p style='text-align:left;padding-left:20px;'>Date Ordered: $result[DateOrdered]</p>";
echo "<p style='text-align:left;padding-left:20px;'>Delivery Date: $result[DeliveryDate]</p>";
echo "<p style='text-align:left;padding-left:20px;'>Delivery Mode: $result[DeliveryMode]</p>";
echo "</div>";
echo "</div>";

echo "<div class='table-responsive tray-table' >"; // Bootstrap responsive table
echo "<table class='table order-table'>"; // Start of table
echo "<thead class='brown-text'>"; // start of table's header section
echo "<tr>";
echo "<th>Donuts</th>";
echo "<th>Price (S$)</th>";
echo "<th>Quantity</th>";
echo "<th>Total (S$)</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";




// Get shopping cart items from orderdata result's "ShopCartID"
$qry = "SELECT sci.Name, p.ProductImage, sci.Price, sci.Quantity FROM ShopCartItem sci
        INNER JOIN Product p ON p.ProductID = sci.ProductID
        WHERE sci.ShopCartID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $shopCartID);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Display Cart Items from session variable "Items"
while ($row = $result->fetch_array()) {
    echo "<tr>";
    $imageDir = "Images/Products/".$row["ProductImage"];
    $price = number_format($row["Price"],2);
    
    $total = number_format($price * $row["Quantity"],2);
    echo "<td style='width: 8em;'>
        <span class='order-contents'><img id='donutImg' src=$imageDir></br>$row[Name]</span></td>";
    echo "<td class='order-contents' style='vertical-align:middle'>$price</td>";
    echo "<td class='order-contents' style='vertical-align:middle;'>$row[Quantity]</td>";
    echo "<td class='order-contents' style='vertical-align:middle; margin-bottom:10%'>$total</td>";
    echo "</tr>";
}

if ($orderMessage != NULL)
{
    echo "<tr class='order-contents'>";
    echo "<td colspan='5' id='orderSummaryTitle'>Message: $orderMessage</td>";
    echo "</tr>";
}

$qry = "SELECT SubTotal, Tax, ShipCharge, Discount, Total FROM ShopCart sc
        WHERE ShopCartID = ? AND ShopperID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("ii", $shopCartID, $_SESSION["ShopperID"]);
$stmt->execute(); 
$result = $stmt->get_result()->fetch_array();
$stmt->close();

echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331;'class='order-contents-summary'>";
echo "<td colspan='2'  id='orderSummaryTitle'>Subtotal</td>";
echo "<td></td>";
$formattedsubtotal = number_format($result["SubTotal"],2);
echo "<td>S$$formattedsubtotal</td>"; // subtotal
echo "</tr>";
if ($result["Discount"] != 0)
{
    echo "<tr class= 'order-contents-summary'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Discount</td>";
    echo "<td></td>";
    $discount = number_format($result["Discount"],2);
    echo "<td>S$$discount</td>"; // this is delivery charge
    echo "</tr>";
}


echo "<tr class= 'order-contents-summary'>";
echo "<td colspan='2' id='orderSummaryTitle'>Delivery Charge - $deliveryMode Delivery</td>";
echo "<td></td>";
$deliveryCharge = number_format($result["ShipCharge"],2);
echo "<td>S$$deliveryCharge</td>"; // this is delivery charge
echo "</tr>";

echo "<tr class='order-contents-summary'>";
echo "<td colspan='2' id='orderSummaryTitle'>Tax</td>";
echo "<td></td>";
$tax = number_format($result["Tax"],2);
echo "<td>S$$result[Tax]</td>";
echo "</tr>";

echo "<tr style='font-weight:800; color:#DD8331; font-size:20px'>";
echo "<td colspan='2' id='orderSummaryTitle'>Total</td>";
echo "<td></td>";
$total = number_format($result["Total"],2);
echo "<td>S$$total</td>"; // total
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";

include("footer.php"); // Include the Page Layout footer
?>
