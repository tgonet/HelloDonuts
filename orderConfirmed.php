<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if (!isset($_SESSION["OrderID"])) {  
    // redirect to index page if there is no order id session
	header ("Location: index.php");
	exit;
}

echo "<div class='row' style='padding:20px 0 0 20px'>";
echo "<div class='col-sm-12'>";
echo "<a class='back-link' href='category.php'>";
echo "<i class='fas fa-chevron-left back-btn'></i> Back to shopping";
echo "</a>";
echo "</div>";
echo "</div>";


    // Establish connection with SQL in this page
    include_once("mysql_conn.php"); 
    // Get order details
    $qry = "SELECT * FROM OrderData
				WHERE OrderID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["OrderID"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array();
    $orderMessage = $result["Message"];
    $stmt->close();

    echo "<div style='width:100%; margin:auto; text-align:center'>";
    // Headers
    echo "<h2 style='margin-top: 20px !important;'>Checkout Success!</h2>";
    echo "<h3 class='outsideBackground'>Your Order</h3>";

    // Blue container
    echo "<div class='background' id= 'customerOrder'>";

    //Print order
    echo "<div style='text-align:right;padding: 0 50px 20px 0;color:#63200D' onclick='printOrder()'><i class='fas fa-print'></i> Print</div>";

    // Company details
    echo "<div class='row company-details'>";
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
    echo "<div class='row customer-details' style='padding-top:20px;'>";
    //Column 1 - Customer details and order id
    echo "<div class='col-sm-6'>";
    echo "<p style='text-align:left;padding-left:20px;'>Order ID: $_SESSION[OrderID]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Customer Name: $result[ShipName]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Address: $result[ShipAddress]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Email: $result[ShipEmail]</p>";
    echo "</div>";
    //Column 2 - Delivery, payment, and order details
    echo "<div class='col-sm-6' style='width:80%;'>";
    echo "<p style='text-align:left;padding-left:20px;'>Date Ordered: $result[DateOrdered]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Payment Method: $_SESSION[PaymentMethod]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Delivery Date & Time: $result[DeliveryDate], $result[DeliveryTime]</p>";
    echo "<p style='text-align:left;padding-left:20px;'>Delivery Mode: $result[DeliveryMode]</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='table-responsive tray-table' >"; // Bootstrap responsive table
    echo "<table class='table order-table'>"; // Start of table
    echo "<thead class='brown-text'>"; // start of table's header section
    echo "<tr>";
    echo "<th colspan='2'>Donut Name</th>";
    echo "<th>Unit Price (S$)</th>";
    echo "<th>Discount (S$)</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total (S$)</th>";
    echo "</tr>";   
    echo "</thead>";
    echo "<tbody>";
    
    foreach($_SESSION['Items'] as $key=>$item) {
        echo "<tr>";
        $imageDir = "Images/Products/".$item["image"];
        $discount = $item["price"] - $item["offeredPrice"];
        if ($discount != 0)
        {
            $discount = "(".number_format($discount, 2).")";
        }
        else
        {
            $discount = "-";
        }
        echo "<td style='width: 8em;' colspan='2'>
            <span class='order-contents'><img id='donutImg' src=$imageDir></br>$item[name]</span></td>";
        echo "<td class='order-contents' style='vertical-align:middle'>$item[price]</td>";
        
        echo "<td class='order-contents' style='vertical-align:middle;'>$discount</td>";
        echo "<td class='order-contents' style='vertical-align:middle;'>$item[quantity]</td>";
        echo "<td class='order-contents' style='vertical-align:middle; margin-bottom:10%'>$item[total]</td>";
        echo "</tr>";
    }
    
    if ($orderMessage != NULL)
    {
        echo "<tr class='order-contents'>";
        echo "<td colspan='6' id='orderSummaryTitle'>Message: $orderMessage</td>";
        echo "</tr>";
    }

    echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331;'class='order-contents-summary'>";
    echo "<td colspan='2'  id='orderSummaryTitle'>Subtotal</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    if ($_SESSION["DiscountSubTotal"] == $_SESSION["SubTotal"])
    {
        echo "<td>S$$_SESSION[SubTotal]</td>"; // subtotal
    }
    else
    { 
        echo "<td>"; // subtotal
        echo" <span style='text-decoration: line-through; font-weight: 600; font-size: 18px; color: #bfa288;'>S$$_SESSION[SubTotal]</span>"; 
        echo " S$$_SESSION[DiscountSubTotal]</td>";
    }
    echo "</tr>";
    echo "<tr class= 'order-contents-summary'>";
    if ($_SESSION["WaiveDelivery"])
    {
        echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_SESSION[DeliveryMode] Delivery (Waived)</td>";
    }
    else
    {
        echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_SESSION[DeliveryMode] Delivery</td>";
    }
    echo "<td></td>";
    echo "<td></td>";

    if ($_SESSION["DeliveryDiscount"] != 0)
    {
        $waivedDelivery = number_format($_SESSION["DeliveryCharge"]+$_SESSION["DeliveryDiscount"],2);
        echo "<td>
        <span style='text-decoration: line-through; font-weight: 600; font-size: 18px; color: #bfa288;'>S$$_SESSION[DeliveryCharge]</span> 
        S$$waivedDelivery
        </td>"; // this is delivery charge
    }
    else
    {
        echo "<td>S$$_SESSION[DeliveryCharge]</td>"; // this is delivery charge
    }
    echo "</tr>";
    echo "<tr class='order-contents-summary'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Tax</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>S$$_SESSION[Tax]</td>";
    echo "</tr>";
    echo "<tr style='font-weight:800; color:#DD8331; font-size:20px'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Total</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>S$$_SESSION[Total]</td>"; // total
    echo "</tr>";
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";


    echo "<script type='text/javascript'>


   function printOrder() {
        var getFullContent = document.body.innerHTML;
        
        // Add css
        var head = document.getElementsByTagName('HEAD')[0]; 
        var link = document.createElement('link');
        link.rel = 'stylesheet';       
        link.href = 'css/site.css'; 
        head.appendChild(link); 

        var printsection = document.getElementById('customerOrder').innerHTML;
        document.body.innerHTML = printsection;
        window.print();
        document.body.innerHTML = getFullContent; 
    }
    </script>";
    $conn->close();
    include("footer.php"); // Include the Page Layout footer
?>
