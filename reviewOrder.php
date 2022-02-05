<?php
//Detect the current session
session_start();
date_default_timezone_set('Asia/Singapore');
// get from session, redirected from deliveryFunction.php
if (!isset($_SESSION["DeliveryMode"])) {  
    // redirect to delivery mode page if no delivery method is provided using $_SESSION variable
 	header ("Location: deliveryMode.php");
 	exit;
 }

// Include the Page Layout header
include("header.php");
?>

<div class='row' style='padding:20px 0 0 20px'>
    <div class='col-sm-12'>
        <a class='back-link' href='deliveryMode.php'>
            <i class='fas fa-chevron-left back-btn'></i> Back
        </a>
    </div>
</div>

<div style="width:100%; margin:auto; text-align:center">
    <div class='mybreadcrumb'>
        <div class='row'>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content complete' href='shoppingCart.php'>
                    <span class='mybreadcrumb-circle complete'>1</span> My Tray
                </a>
            </div>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content complete' href='deliveryMode.php'>
                    <span class='mybreadcrumb-circle complete'>2</span> Delivery Mode
                </a>
            </div>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content complete' href=''>
                    <span class='mybreadcrumb-circle complete'>3</span> Review Order
                </a>
            </div>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content' href=''>
                    <span class='mybreadcrumb-circle'>4</span> Make Payment
                </a>
            </div>
        </div>
    </div>

<?php
// Establish connection with SQL in this page
include_once("mysql_conn.php");

// Table headers
echo "<h3 class='outsideBackground'>Your Order</h3>";
echo "<div class='tray-background'>";
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
// Display Cart Items from session variable "Items"
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


echo "<tr class='order-contents'>";
echo "<td colspan='6' id='orderSummaryTitle'>Expected Delivery Date: $_SESSION[DeliveryDate]</td>";
echo "</tr>";

echo "<tr class='order-contents'>";
echo "<td colspan='6' id='orderSummaryTitle'>Expected Delivery Time: $_SESSION[DeliveryTime]</td>"; //KIV
echo "</tr>";


// Get Current GST Rate from SQL
$qry = "SELECT TaxRate FROM GST 
        WHERE EffectiveDate <= CURRENT_DATE()
        ORDER BY EffectiveDate DESC
        LIMIT  1";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$stmt->close();
$taxRate = $result["TaxRate"]/100;

// Save Tax Amount as session variable "Tax"
$_SESSION["Tax"] = number_format($_SESSION["SubTotal"]*$taxRate,2);
$_SESSION["Total"] = number_format($_SESSION["DiscountSubTotal"] + $_SESSION["DeliveryCharge"] + $_SESSION["DeliveryDiscount"] + $_SESSION["Tax"],2);

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
    echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_SESSION[DeliveryMode] Delivery (Waived)</td>";
else
    echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_SESSION[DeliveryMode] Delivery</td>";
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
echo "<h3 class='outsideBackground'>Customize Your Order</h3>";
echo "<div class='background'style='padding: 2% !important;'>";
echo "<form style='margin: auto; width:100%' action='checkoutProcess.php' method='post'>";
echo "<div class='form-group row'>";
echo "<label for='Message' class='col-md-4 col-form-label tray-donut-name'>Add a message for receiver:</label>";
echo "<div class='col-md-7' style='padding:0 30px 0 30px'>";
echo "<input type='text' class='form-control textfield' id='Message' name='Message'>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<h3 class='outsideBackground'>Payment Method</h3>";
echo "<div class='background'>";
echo "<input type='radio' name='paymentMethod' value='paypal' checked>";
echo "<img src='https://1000logos.net/wp-content/uploads/2021/04/Paypal-logo.png' style='width:30%'>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-md-12'>";
echo "<button type='submit' action='checkoutProcess.php'>Proceed to Payment</button>";
echo "</form>";
echo "</div>";
echo "</div>"; 
echo "</div>";

$conn->close(); // Close database connection
include("footer.php"); // Include the Page Layout footer
?>
