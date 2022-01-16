<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");

// Check if user logged in and cart is not empty
/* UNCOMMENT ONCE CART & CATALOGUE DONE
if (! isset($_SESSION["ShopperID"]) || ($_SESSION["NumCartItem"]) == 0)) { 
	// redirect to login page if the session variable shopperid is not set or cart is empty
	header ("Location: login.php");
	exit;
}
*/
?>

<div class="row" style="padding:20px 0 0 20px">
    <div class="col-sm-12">
        <div class="circle"><</div>
        <div style="color:#82BCC7; padding-left:8px; display:inline-block">Back</div>
    </div>
</div>

<div style="width:100%; margin:auto; text-align:center">
    <div class="mybreadcrumb">
        <div class="row">
            <div class="col-sm-3">
                <div class="circle mybreadcrumb-circle complete">1</div>
                <div class="mybreadcrumb-content complete">My Tray</div>
            </div>
            <div class="col-sm-3">
                <div class="circle mybreadcrumb-circle complete">2</div>
                <div class="mybreadcrumb-content complete">Delivery Mode</div>
            </div>
            <div class="col-sm-3">
                <div class="circle mybreadcrumb-circle complete">3</div>
                <div class="mybreadcrumb-content complete">Review</div>
            </div>
            <div class="col-sm-3">
                <div class="circle mybreadcrumb-circle">4</div>
                <div class="mybreadcrumb-content">Payment</div>
            </div>
        </div>
    </div>
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
        <form action='checkoutProcess.php' method='post'>
            <input type="radio" name="paymentMethod" value="paypal" checked>
            <img src="https://1000logos.net/wp-content/uploads/2021/04/Paypal-logo.png" style="width:30%">
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

// Get Current GST Rate from SQL
$qry = "SELECT MAX(EffectiveDate), TaxRate FROM GST
WHERE EffectiveDate <= CURRENT_DATE()";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$taxRate = $result["TaxRate"];

// Save Tax Amount as session variable "Tax"
$_SESSION["Tax"] = round($_SESSION["Subtotal"]*$taxRate,2);

// Table headers
echo "<h3 class="outsideBackground">Your Order</h3>";
echo " <div class='background'>";
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
    /* MAKE AMENDMENTS ACCORD. TO SHOPPING CART'S SESSION VARIABLE
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
    echo "<td><img id='donutImg' src='Images/Developers/'. $productName. '.jpg'></br>
        $productName</td>";
    echo "<td style='vertical-align:middle'>$price</td>";
    
    echo "<td style='vertical-align:middle'>$discount</td>";
    echo "<td style='vertical-align:middle'>$quantity</td>";
    echo "<td style='vertical-align:middle; margin-bottom:10%'>$total</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "<h3 class='outsideBackground'>Payment Method</h3>";
echo "<div class='background'>";
echo "<form action='checkoutProcess.php' method='post'>";
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
