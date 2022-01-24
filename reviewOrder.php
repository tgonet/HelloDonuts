<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
date_default_timezone_set('Asia/Singapore');

if (!$_POST) {  
    // redirect to shopping cart page if no delivery method is provided using $_POST method
	header ("Location: deliveryMode.php");
	exit;
}

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
echo "<table class='table'>"; // Start of table
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
    $price = number_format($item["price"],2);
    $discount = "-";
    $totalDiscount = 0;
    if ($item["discount"] != 0)
    {
        $discount = ($price - $item["discount"]);
        $totalDiscount += $discount * $item["quantity"];
        $discount = "(".number_format($discount, 2).")";
    }
    $total = number_format($item["total"],2);
    echo "<td style='width: 8em;' colspan='2'>
        <span class='order-contents'><img id='donutImg' src=$imageDir></br>$item[name]</span></td>";
    echo "<td class='order-contents' style='vertical-align:middle'>$price</td>";
    
    echo "<td class='order-contents' style='vertical-align:middle;'>$discount</td>";
    echo "<td class='order-contents' style='vertical-align:middle;'>$item[quantity]</td>";
    echo "<td class='order-contents' style='vertical-align:middle; margin-bottom:10%'>$total</td>";
    echo "</tr>";
}

// Get today's date
$_SESSION["DeliveryDate"] = new DateTime('now');

// Calculate Delivery Charge and Date
switch ($_POST["delivery_mode"])
{
    case "Express":
    {
        $_SESSION["DeliveryCharge"] = 5;
        $_SESSION["DeliveryDate"]->modify('+2 hour');
        break;
    }
        
    case "Normal":
    {
        $_SESSION["DeliveryCharge"] = 2;
        $_SESSION["DeliveryDate"]->modify('+1 day');
        break;
    }
}

echo "<tr class='order-contents'>";
$formattedDeliveryDate = $_SESSION["DeliveryDate"]->format('Y-m-d');
echo "<td colspan='6' id='orderSummaryTitle'>Expected Delivery Date: $formattedDeliveryDate</td>";
echo "</tr>";

echo "<tr class='order-contents'>";
echo "<td colspan='6' id='orderSummaryTitle'>Expected Delivery Time: to be implemented</td>"; //KIV
echo "</tr>";


$isWaived = "";
if (isset($_POST["DeliveryWaived"]))
{
    $_SESSION["DeliveryDiscount"] = $_SESSION["DeliveryCharge"];
    $_SESSION["DeliveryCharge"] = 0;
    $isWaived  = " (Waived)";
}

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
$totalBeforeGST = $_SESSION["SubTotal"]+$_SESSION["DeliveryCharge"]-$totalDiscount;
$_SESSION["Tax"] = round($totalBeforeGST*$taxRate,2);
$_SESSION["Total"] = $totalBeforeGST + $_SESSION["Tax"];

echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331;'class='order-contents-summary'>";
echo "<td colspan='2'  id='orderSummaryTitle'>Subtotal</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
$subtotal = number_format($_SESSION["SubTotal"],2);
echo "<td>S$$subtotal</td>"; // subtotal
echo "</tr>";
echo "<tr class= 'order-contents-summary'>";
echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_POST[delivery_mode] Delivery$isWaived</td>";
echo "<td></td>";
echo "<td></td>";
$deliveryCharge = number_format($_SESSION["DeliveryCharge"],2);
echo "<td>S$$deliveryCharge</td>"; // this is delivery charge
echo "</tr>";
if ($totalDiscount != 0)
{
    $totalDiscount = "(".number_format($totalDiscount, 2).")";
    echo "<tr class='order-contents-summary'>";
    echo "<td colspan='2' id='orderSummaryTitle'>Discount</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>S$$totalDiscount</td>"; // this is discount
    echo "</tr>";
}
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
echo "<h3 class='outsideBackground'>Payment Method</h3>";
echo "<div class='background'>";
echo "<form style='margin: auto' action='checkoutProcess.php' method='post'>";
echo "<input type='radio' name='paymentMethod' value='paypal' checked>";
echo "<img src='https://1000logos.net/wp-content/uploads/2021/04/Paypal-logo.png' style='width:30%'>";
echo "</div>";
echo "<h3 class='outsideBackground'>Customize Your Order</h3>";
echo "<div class='background'>";
echo "<div class='form-group row' style='margin-bottom:40px;'>";
echo "<label for='message' class='col-sm-3 col-form-label tray-donut-name'>Add a Message:</label>";
echo "<div class='col-sm-8' style='padding:0 40px 0 40px'>";
echo "<input type='text' class='form-control textfield' id='message' name='message'>";
echo "</div>";
echo "</div>";
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
