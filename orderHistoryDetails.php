<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

<?php
if(isset($_SESSION["ShopperID"])) {
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

    echo "<div style='width:100%; margin:auto; text-align:center'>";

    // Headers
    echo "<h3 class='outsideBackground'>Order $orderId</h3>";

    // Blue container
    echo "<div class='background' id= 'customerOrder'>";

    //Print order
    echo "<div style='text-align:right;padding: 0 50px 20px 0;color:#63200D' onclick='printOrder()'><i class='fas fa-print'></i> Print</div>";

    // Order details
    echo "<div class='row' style='padding-top:20px;'>";
    //Column 1 - Customer details and order id
    echo "<div class='col-sm-6'>";
    echo "<p style='text-align:left;padding-left:20px;'>Order ID: $_SESSION[OrderID]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Customer Name: $result[ShipName]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Address: $result[ShipAddress]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Tel: $result[ShipPhone]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Email: $result[ShipEmail]</p>";
    echo "</div>";
    //Column 2 - Delivery, payment, and order details
    echo "<div class='col-sm-6' style='width:80%;'>";
    echo "<p style='text-align:left;padding-left:20px;'>Date Ordered: $result[DateOrdered]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Payment Method: $_SESSION[PaymentMethod]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Delivery Date: $result[DeliveryDate]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Delivery Mode: $result[DeliveryMode]</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='table-responsive' style='padding-top:20px'>";
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


    $qry = "SELECT Name, ProductImage, p.Price as NormalPrice,
            i.Price as Price, i.Quantity as Quantity, (i.Price * i.Quantity) AS Total
            FROM ShopCartItem i LEFT JOIN Product p
            ON i.ProductID = p.ProductID
            WHERE ShopCartID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["ShopCartID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $discountTotal = 0;
    $subtotal = 0;
    $totalPayment = 0;

    // Display Cart Items from session variable "Items"
    while ($row = $result->fetch_array()) {
        $normalPrice = number_format($row["NormalPrice"],2);
        $total = number_format($row["Total"],2);
        $discount = number_format($row["NormalPrice"] - $row["Price"],2);
        echo "<tr>";
        echo "<td><img id='donutImg' src='Images/Products/$row[ProductImage]'></br>
            $row[ProductTitle]</td>";
        echo "<td style='vertical-align:middle'>$normalPrice</td>";
        if ($discount > 0)
        {
            echo "<td style='vertical-align:middle'>$discount</td>";
        }
        else
        {
            echo "<td style='vertical-align:middle'>-</td>";
        }
        echo "<td style='vertical-align:middle'>$row[Quantity]</td>";
        echo "<td style='vertical-align:middle; margin-bottom:10%'>$total</td>";
        echo "</tr>";
        $discountTotal += $discount;
        $subtotal += $total;
        $totalPayment += $subtotal;
    }
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
    $discountTotal = number_format($discountTotal, 2);
    $subtotal = number_format($subtotal, 2);
    $tax = number_format($_SESSION["Tax"], 2);
    $totalPayment = $totalPayment - $discountTotal + $_SESSION["Tax"];
    $totalPayment = number_format($totalPayment, 2);
    echo "<tr style='font-weight:550'>";
    echo "<td colspan='5' id='orderSummaryTitle'>Message: $orderMessage</td>";
    echo "</tr>";
    echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331; font-weight:550'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Subtotal</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>S$$subtotal</td>"; // subtotal
    echo "</tr>";
    echo "<tr style='font-weight:550'>";
    echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $deliveryMode Delivery</td>";
    echo "<td></td>";
    echo "<td>S$5.00</td>"; // this is delivery charge
    echo "</tr>";
    echo "<tr style='font-weight:550'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Discount</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>(S$$discountTotal)</td>"; // this is discount
    echo "</tr>";
    echo "<tr style='font-weight:550'>";
    echo "<td colspan='2' id='orderSummaryTitle'>GST</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>S$$tax</td>";
    echo "</tr>";
    echo "<tr style='font-weight:bold; color:#DD8331; font-size:18px'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Total</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>S$$totalPayment</td>"; // total
    echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";


    echo "<script type='text/javascript'>


    function printOrder() {
            var getFullContent = document.body.innerHTML;
            var printsection = document.getElementById('customerOrder').innerHTML;
            document.body.innerHTML = printsection;
            window.print();
            document.body.innerHTML = getFullContent;
        }
        </script>";
    include("footer.php"); // Include the Page Layout footer
}
?>