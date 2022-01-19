<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
date_default_timezone_set('Asia/Singapore');

// Check if user logged in, cart is not empty,and delivery method is provided -- prob dunnid
/* UNCOMMENT ONCE CART & CATALOGUE DONE
else if (($_SESSION["NumCartItem"]) == 0) || $_SESSION["DeliveryMethod"]) {  -- prob dunnid
	// redirect to index page if cart is empty
	header ("Location: index.php");
	exit;
}
else if (!$_POST) {  -- just need this, but test first
    // redirect to shopping cart page if no delivery method is provided using $_POST method
	header ("Location: shoppingCart.php");
	exit;
}
*/

/* Session variables from shopping cart:
$_SESSION['NumCartItem']
$_SESSION['Items']
- discount ($discount)
- unit price ($price)
- quantity ($quantity)
- total ($total)
- donut name ($name)
- donut image ($image)
- donut productid ($productId)

$_SESSION['Subtotal']
$_SESSION['DeliveryCharge']
$_SESSION['DeliveryMode']
$_SESSION['Discount']

Other session variables:
$_SESSION['ShopperID']
*/
?>

<div class="row" style="padding:20px 0 0 20px">
    <a href="deliveryMode.php">
        <div class="circle"><i class="fas fa-chevron-circle-left" style='color:#82BCC7'></i></div>
        <div style="color:#82BCC7; padding-left:8px; display:inline-block">Back</div>
    </a>
</div>

<div style="width:100%; margin:auto; text-align:center">
    <div class="mybreadcrumb">
        <div class="row">
            <div class="col-sm-3">
                <a href="shoppingCart.php">
                    <div class="circle mybreadcrumb-circle complete">1</div>
                    <div class="mybreadcrumb-content complete">My Tray</div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="deliveryMode.php">
                    <div class="circle mybreadcrumb-circle complete">2</div>
                    <div class="mybreadcrumb-content complete">Delivery Mode</div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="#">
                    <div class="circle mybreadcrumb-circle complete">3</div>
                    <div class="mybreadcrumb-content complete">Review</div>
                </a>
            </div>
            <div class="col-sm-3">
                <div class="circle mybreadcrumb-circle">4</div>
                <div class="mybreadcrumb-content">Payment</div>
            </div>
        </div>
    </div>
    <!--From here, delete html-->
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
            <label for="message" class="col-sm-3 col-form-label">Message:</label>
            <div class="col-sm-8" style="padding:0 40px 0 40px">
                <input type="text" class="form-control textfield" id="message" name="message" required>
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


<?php
/* UNCOMMENT ONCE CART & CATALOGUE DONE

// Establish connection with SQL in this page
include_once("mysql_conn.php");

// Get today's date
$_SESSION["DeliveryDate"] = new DateTime('now');

// Calculate Delivery Charge and Date
switch ($_SESSION["DeliveryMode"])
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

if ($_POST["DeliveryWaived"])
{
    $_SESSION["DeliveryDiscount"] = $_SESSION["DeliveryCharge"];
}

// Get Current GST Rate from SQL
$qry = "SELECT MAX(EffectiveDate), TaxRate FROM GST
WHERE EffectiveDate <= CURRENT_DATE()";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$taxRate = $result["TaxRate"]/100;

// Save Tax Amount as session variable "Tax"
$totalBeforeGST = $_SESSION["Subtotal"]+$_SESSION["DeliveryCharge"]-$_SESSION["Discount"];
$_SESSION["Tax"] = round($totalBeforeGST*$taxRate,2);
$_SESSION["Total"] = $totalBeforeGST + $_SESSION["Tax"];

// Table headers
echo "<h3 class="outsideBackground">Your Order</h3>";
echo "<div class='background'>";
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
// Display Cart Items from session variable "Items"
foreach($_SESSION['Items'] as $key=>$item) {
    echo "<tr>";
    echo "<td><img id='donutImg' src='Images/Products/'. $item[image]></br>
        $item[name]</td>";
    echo "<td style='vertical-align:middle'>$item[price]</td>";
    
    echo "<td style='vertical-align:middle'>$item[discount]</td>";
    echo "<td style='vertical-align:middle'>$item[quantity]</td>";
    echo "<td style='vertical-align:middle; margin-bottom:10%'>$item[total]</td>";
    echo "</tr>";
}
echo "<tr style='font-weight:550'>";
$formattedDeliveryDate =$_SESSION["DeliveryDate"]->format('Y-m-d');
echo "<td colspan='5' id='orderSummaryTitle'>Expected Delivery Date: $formattedDeliveryDate</td>";
echo "</tr>";

echo "<tr style='font-weight:550'>";
echo "<td colspan='5' id='orderSummaryTitle'>Expected Delivery Date: $_SESSION[DeliveryTime]</td>"; //KIV
echo "</tr>";

echo "<tr style='margin-top:20px;border-top: solid 2px; border-color:#DD8331; font-weight:550'>";
echo "<td colspan='2' id='orderSummaryTitle'>Subtotal</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>$_SESSION[Subtotal]</td>"; // subtotal
echo "</tr>";
echo "<tr style='font-weight:550'>";
echo "<td colspan='3' id='orderSummaryTitle'>Delivery Charge - $_SESSION[DeliveryMode] Delivery'</td>;
echo "<td></td>";
if (isset($_SESSION[DeliveryDiscount]))
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
echo "<div class='row'>";
echo "<div class='col-md-12'>";
echo "<button type='submit' action='checkoutProcess.php'>Proceed to Payment</button>";
echo "</div>";
echo "</div>"; 
echo "</div>";

$conn->close(); // Close database connection

*/
include("footer.php"); // Include the Page Layout footer

?>
