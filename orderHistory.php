<?php 
    session_start(); // Detect the current session
    
    if (!isset($_SESSION["ShopperID"]))
    {
        header("Location: login.php");
        exit;
    }
    
    include("header.php"); // Include the Page Layout header
    include_once("mysql_conn.php"); // Establish connection with SQL in this page

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
    echo "<th>Total (S$)</th>";
    echo "<th></th>";
    echo "</tr>";   
    echo "</thead>";
    echo "<tbody>";

    // Get Order History
    $qry = "SELECT OrderID,DateOrdered,DeliveryDate,OrderStatus,sc.Total FROM OrderData o 
    INNER JOIN Shopcart sc ON o.ShopCartID = sc.ShopCartID 
    INNER JOIN Shopper s ON sc.ShopperID = s.ShopperID
    WHERE s.ShopperID = ?
    ORDER BY DateOrdered DESC";
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
                echo "<td class='text-danger order-contents' style='vertical-align:middle'>Cancelled</td>";
                break;
            case 1:
                echo "<td class='text-secondary order-contents'style='vertical-align:middle'>Received</td>";
                break;
            case 2:
                echo "<td  class='text-primary order-contents' style='vertical-align:middle'>Ready for shipment</td>";
                break;
            case 3:
                echo "<td  class='text-success order-contents' style='vertical-align:middle'>Delivered</td>";
                break;

        }
        $total = number_format($row["Total"],2);
        echo "<td style='vertical-align:middle'>$total</td>";

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

        include("footer.php"); // Include the Page Layout footer
?>
