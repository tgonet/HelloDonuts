<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
$_SESSION["OrderID"] = 1;
$_SESSION["ShopCartID"] = 1;
$_SESSION["PaymentMethod"] = "PayPal";
$_SESSION["Tax"] = 0.5;
$_SESSION["ShopperID"] = 1;
if(isset($_SESSION["ShopperID"])) {
    // Establish connection with SQL in this page
    include_once("mysql_conn.php"); 

    echo "<div style='width:100%; margin:auto; text-align:center'>";

    // Headers
    echo "<h2 style='margin-top: 20px !important;'>Order History</h2>";

    // Blue container
    echo "<div class='background'>";

    echo "<div class='table-responsive' style='padding-top:20px'>";
    echo "<table class='table table-hover'>";
    echo "<thead class= 'order-header'>";
    echo "<tr>";
    echo "<th>Order No.</th>";
    echo "<th>Order Date</th>";
    echo "<th>Delivery Date</th>";
    echo "<th>Status</th>";
    echo "<th>Total</th>";
    echo "<th></th>";
    echo "</tr>";   
    echo "</thead>";
    echo "<tbody>";

    // Get Order History
    $qry = "SELECT OrderID,DateOrdered,DeliveryDate,OrderStatus,sc.Total FROM OrderData o 
    INNER JOIN Shopcart sc ON o.ShopCartID = sc.ShopCartID 
    INNER JOIN Shopper s ON sc.ShopperID = s.ShopperID
    WHERE s.ShopperID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["ShopperID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Display Order History
    while ($row = $result->fetch_array()) {
        echo "<tr>";
        echo "<td style='vertical-align:middle'>$row[OrderID]</td>";
        echo "<td style='vertical-align:middle'>$row[DateOrdered]</td>";
        echo "<td style='vertical-align:middle'>$row[DeliveryDate]</td>";
        $status = $row["OrderStatus"];
        switch ($status){
            case 0:
                echo "<td style='vertical-align:middle'>Cancelled</td>";
            case 1:
                echo "<td style='vertical-align:middle'>Received</td>";
            case 2:
                echo "<td style='vertical-align:middle'>Ready for shipment</td>";
            case 3:
                echo "<td style='vertical-align:middle'>Delivered</td>";

        }
        $total = number_format($row["Total"],2);
        echo "<td style='vertical-align:middle'>S$$total</td>";

        $order = "orderHistoryDetails.php?orderid=$row[OrderID]";
        echo "<td style='vertical-align:middle'>
                <a href=$order style='color: #63200d'><i class='fas fa-arrow-circle-right'></i></a>
            </td>";
        echo "</tr>";;
    }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        /*
            echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
            echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
            echo '<a href="index.php">Continue shopping</a></p>';
            */  
    } 
    include("footer.php"); // Include the Page Layout footer
?>
