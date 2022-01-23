<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
date_default_timezone_set('Asia/Singapore');

if (!$_POST) {  
    // redirect to shopping cart page if no delivery method is provided using $_POST method
	header ("Location: shoppingCart.php");
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
/*
if ($_POST["DeliveryWaived"])
{
    $_SESSION["DeliveryDiscount"] = $_SESSION["DeliveryCharge"];
}
*/
// Get Current GST Rate from SQL
$qry = "SELECT MAX(EffectiveDate), TaxRate FROM GST
WHERE EffectiveDate <= CURRENT_DATE()";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$stmt->close();
$taxRate = $result["TaxRate"]/100;

// Save Tax Amount as session variable "Tax"
$totalBeforeGST = $_SESSION["Subtotal"]+$_SESSION["DeliveryCharge"]-$_SESSION["Discount"];
$_SESSION["Tax"] = round($totalBeforeGST*$taxRate,2);
$_SESSION["Total"] = $totalBeforeGST + $_SESSION["Tax"];

// Table headers
echo "<h3 class='outsideBackground'>Your Order</h3>";
echo "<div class='tray-background'>";
echo "<div class='table-responsive tray-table' >"; // Bootstrap responsive table
echo "<table class='table'>"; // Start of table
echo "<thead class='brown-text'>"; // start of table's header section
/*
echo "<div class='background'>";
echo "<div class='table-responsive'>";
echo "<table class='table table-hover'>";
echo "<thead class= 'order-header'>";
*/
echo "<tr>";
echo "<th>Donuts</th>";
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
    if ($item["discount"] != 0)
    {
        $discount = $price - $item["discount"];
        $discount = "(".number_format($discount, 2).")";
    }
    $total = number_format($item["total"],2);
    echo "<td style='width: 8em;'><img class='donut-img' id='donutImg' src=$imageDir></br>
        <span class='tray-donut-name'>$item[name]</span></td>";
    echo "<td style='vertical-align:middle;font-weight: 600;'>$price</td>";
    
    echo "<td style='vertical-align:middle;font-weight: 600;'>$discount</td>";
    echo "<td style='vertical-align:middle;font-weight: 600;'>$item[quantity]</td>";
    echo "<td style='vertical-align:middle;font-weight: 600; margin-bottom:10%'>$item[total]</td>";
    echo "</tr>";
}

echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331; font-weight:550'>";
echo "<td colspan='2' id='orderSummaryTitle'>Subtotal</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>$_SESSION[Subtotal]</td>"; // subtotal
echo "</tr>";
echo "<tr style='font-weight:550'>";
echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_POST[delivery_mode] Delivery</td>";
echo "<td></td>";
if (isset($_SESSION["DeliveryDiscount"]))
{
    echo "<td>$_SESSION[DeliveryCharge]-$_SESSION[DeliveryDiscount]</td>"; // this is delivery charge
}
else
{
    echo "<td>$_SESSION[DeliveryCharge]</td>"; // this is delivery charge
}
echo "</tr>";
echo "<tr style='font-weight:550'>";
echo "<td colspan='2' id='orderSummaryTitle'>Discount</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>($_SESSION[Discount])</td>"; // this is discount
echo "</tr>";
echo "<tr style='font-weight:550'>";
echo "<td colspan='2' id='orderSummaryTitle'>Tax</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>$_SESSION[Tax]</td>";
echo "</tr>";
echo "<tr style='font-weight:bold; color:#DD8331; font-size:18px'>";
echo "<td colspan='2' id='orderSummaryTitle'>Total</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>$_SESSION[Total]</td>"; // total
echo "</tr>";

echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331; font-weight:550'>";
$formattedDeliveryDate = $_SESSION["DeliveryDate"]->format('Y-m-d');
echo "<td colspan='5' id='orderSummaryTitle'>Expected Delivery Date: $formattedDeliveryDate</td>";
echo "</tr>";

echo "<tr style='font-weight:550'>";
echo "<td colspan='5' id='orderSummaryTitle'>Expected Delivery Time: to be implemented$_SESSION[DeliveryTime]</td>"; //KIV
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
echo "</form>";
echo "</div>";
echo "<h3 class='outsideBackground'>Customize Your Order</h3>";
echo "<div class='background'>";
echo "<div class='form-group row' style='margin-bottom:40px;'>";
echo "<label for='message' class='col-sm-3 col-form-label tray-donut-name'>Add a Message:</label>";
echo "<div class='col-sm-8' style='padding:0 40px 0 40px'>";
echo "<input type='text' class='form-control textfield' id='message' name='message'>";
echo "</div>";
echo "</div>";
echo "</form>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-md-12'>";
echo "<button type='submit' action='checkoutProcess.php'>Proceed to Payment</button>";
echo "</div>";
echo "</div>"; 
echo "</div>";

$conn->close(); // Close database connection
include("footer.php"); // Include the Page Layout footer
?>


<?php 
/*
    <h3 class="outsideBackground">Your Order</h3>
    <div class='background'>
        <div class='table-responsive'>
            <table class='table table-hover'>
                <thead class= 'order-header'>
                    <tr>
                        <th>Donuts</th>
                        <th>Unit Price (S$)</th>
                        <th>Discount (S$)</th>
                        <th>Quantity</th>
                        <th>Total (S$) </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img id='donutImg' src='Images/Developers/joey.jpg'></br>
                            Donut 1</td>
                        <td style="vertical-align:middle">5.00</td> <!--unit price-->
                        <td style="vertical-align:middle">(2.00)</td> <!--discount-->
                        <td style="vertical-align:middle">1</td> <!--quantity-->
                        <td style="vertical-align:middle; margin-bottom:10%">3.00</td> <!--total-->
                    </tr>
                    <tr>
                        <td><img id='donutImg' src='Images/Developers/joey.jpg'></br>
                            Donut 1</td>
                        <td style='vertical-align:middle'>5.00</td> <!--unit price-->
                        <td style='vertical-align:middle'>(2.00)</td> <!--discount-->
                        <td style='vertical-align:middle'>1</td> <!--quantity-->
                        <td style='vertical-align:middle; margin-bottom:10%'>3.00</td> <!--total-->
                    </tr>
                    <tr style='font-weight:550'>
                        <td colspan='5' id='orderSummaryTitle'>Expected Delivery Date: sometime</td>
                    </tr>
                    <tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331; font-weight:550'>
                        <td colspan='2' id='orderSummaryTitle'>Subtotal</td>
                        <td></td>
                        <td></td>
                        <td>S$10.00</td>
                    </tr>
                    <tr style='font-weight:550'>
                        <td colspan='3' id='orderSummaryTitle'>Delivery Charge - Express Delivery</td>
                        <td></td>
                        <td>S$5.00</td>
                    </tr>
                    <tr style='font-weight:550'>
                        <td colspan='2' id='orderSummaryTitle'>Discount</td>
                        <td></td>
                        <td></td>
                        <td>(S$2.00)</td>
                    </tr>
                    <tr style='font-weight:550'>
                        <td colspan='2' id='orderSummaryTitle'>GST</td>
                        <td></td>
                        <td></td>
                        <td>$0.70</td>
                    </tr>
                    <tr style='font-weight:bold; color:#DD8331; font-size:18px'>
                        <td colspan='2' id='orderSummaryTitle'>Total</td>
                        <td></td>
                        <td></td>
                        <td>S$5.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h3 class="outsideBackground">Payment Method</h3>
    <div class='background'>
        <form style = 'margin: auto' action='checkoutProcess.php' method='post'>
            <input type="radio" name="paymentMethod" value="paypal" checked>
            <img src="https://1000logos.net/wp-content/uploads/2021/04/Paypal-logo.png" style="width:30%">
    </div>

    <h3 class="outsideBackground">Customize Your Order</h3>
    <div class='background'>
        <div class="form-group row" style="margin-bottom:40px;">
            <label for="message" class="col-sm-3 col-form-label">Add a Message:</label>
            <div class="col-sm-8" style="padding:0 40px 0 40px">
                <input type="text" class="form-control textfield" id="message" name="message">
            </div>
        </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" action="checkoutProcess.php">Proceed to Payment</button>
        </div>
    </div>
</div>
*/
?>
