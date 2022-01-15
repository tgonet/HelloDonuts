<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
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

/*
// Check if user logged in and cart is not empty
if (! isset($_SESSION["ShopperID"]) || ($_SESSION["NumCartItem"]) == 0)) { 
	// redirect to login page if the session variable shopperid is not set or cart is empty
	header ("Location: login.php");
	exit;
}
*/
/*
include_once("mysql_conn.php");

// Get Current GST Tax Rate
$qry = "SELECT MAX(EffectiveDate), TaxRate FROM GST
WHERE EffectiveDate <= CURRENT_DATE()";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$taxRate = $result["TaxRate"];
$_SESSION["Tax"] = round($_SESSION["Subtotal"]*$taxRate,2);

// Retrieve from database and display order review in a table
$qry = "SELECT *, (Price*Quantity) AS Total
        FROM ShopCartItem WHERE ShopCartID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $_SESSION["Cart"]);	// "i" - integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    echo "<div style='width:100%; margin:auto; text-align:center'>";
        echo "<h3 class='outsideBackground'>Your Order</h3>";
    echo "<div class='background'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover'>";
    echo "<thead class= 'cart-header'>";
    echo "<tr>";
    echo "<th>Donuts</th>";
    echo "<th>Unit Price (S$)</th>";
    echo "<th>Discount (S$)</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total (S$)</th>";
    echo "</tr>";             
    echo "</thead>";

    while ($row = $result->fetch_array()) {
        echo "<tr>"	;
        echo "<td style='width:50%'>$row[Name]<br/>";
        echo "Product ID: $row[ProductID]</td>";
        $formattedPrice = number_format($row["Price"], 2);
        echo "<td>$formattedPrice </td>";
        echo "<td>";  // Column for update quantity of purchase
        echo "<form action='cartFunctions.php' method='post'>";
        echo "<select name='quantity' onChange='this.form.submit()'>";
        for ($i = 1; $i <= 10; $i++) {	// To populate drop-down list from 1 to 10
            if ($i == $row["Quantity"])
                // Select drop-down list item with value same as quantity of purchase
                $selected = "selected";
            else
                $selected = "";	// No specific item is selected
            echo "<option value='$i' $selected>$i</option>";
        }
        echo "</select>";
        echo "<input type ='hidden' name='action' value='update'/>";
        echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
        echo "</form>";
        echo "</td>";
        $formattedTotal = number_format($row["Total"], 2);
        echo "<td>$formattedTotal</td>";
        echo "<td>"; // Column for remove item from shopping cart
        echo "<form action = 'cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='remove' />";
        echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
        echo "<input type='image' src='images/trash-can.png' title='Remove Item'/>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        // To Do 6 (Practical 5):
        // Store the shopping cart items in session variable as an associate array
        $_SESSION["Items"][] = array("productId"=>$row["ProductID"],
                                        "name"=>$row["Name"],
                                        "price"=>$row["Price"],
                                        "quantity"=>$row["Quantity"]);
        // Accumulate the running sub-total
        $subTotal += $row["Total"];
    }
    echo "</tbody>"; // End of table's body section
    echo "</table>"; // End of table
    echo "</div>"; // End of Bootstrap responsive table
    echo "<hr>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}




    echo "<div class='table-responsive' >"; // Bootstrap responsive table
    echo "<table class='table table-hover'>"; // Start of table
    echo "<thead class= 'cart-header'>";	// Start if table's header section
    echo "<tr>"; //Start of header row
    echo "<th width='250px'>Item</th>";
    echo "<th width='90px'>Price (S$) </th>";
    echo "<th width='60px'>Quantity</th>";
    echo "<th width='120px'>Total (S$) </th>";
    echo "<th> &nbsp; </th>"; // denote non-breaking space (remove extra spaces)
    echo "</tr>"; // End of header row
    echo "</thead>"; // End of table's header section
    // To Do 5 (Practical 5):
    // Declare an array to store the shopping cart items in session variable 
    $_SESSION["Items"]=array();
    // To Do 3 (Practical 4): 
    // Display the shopping cart content
    $subTotal = 0; // Declare a variable to compute subtotal before tax
    echo "<tbody>"; // Start of table's body section
    while ($row = $result->fetch_array()) {
        echo "<tr>"	;
        echo "<td style='width:50%'>$row[Name]<br/>";
        echo "Product ID: $row[ProductID]</td>";
        $formattedPrice = number_format($row["Price"], 2);
        echo "<td>$formattedPrice </td>";
        echo "<td>";  // Column for update quantity of purchase
        echo "<form action='cartFunctions.php' method='post'>";
        echo "<select name='quantity' onChange='this.form.submit()'>";
        for ($i = 1; $i <= 10; $i++) {	// To populate drop-down list from 1 to 10
            if ($i == $row["Quantity"])
                // Select drop-down list item with value same as quantity of purchase
                $selected = "selected";
            else
                $selected = "";	// No specific item is selected
            echo "<option value='$i' $selected>$i</option>";
        }
        echo "</select>";
        echo "<input type ='hidden' name='action' value='update'/>";
        echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
        echo "</form>";
        echo "</td>";
        $formattedTotal = number_format($row["Total"], 2);
        echo "<td>$formattedTotal</td>";
        echo "<td>"; // Column for remove item from shopping cart
        echo "<form action = 'cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='remove' />";
        echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
        echo "<input type='image' src='images/trash-can.png' title='Remove Item'/>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
        // To Do 6 (Practical 5):
        // Store the shopping cart items in session variable as an associate array
        $_SESSION["Items"][] = array("productId"=>$row["ProductID"],
                                        "name"=>$row["Name"],
                                        "price"=>$row["Price"],
                                        "quantity"=>$row["Quantity"]);
        // Accumulate the running sub-total
        $subTotal += $row["Total"];
    }
    echo "</tbody>"; // End of table's body section
    echo "</table>"; // End of table
    echo "</div>"; // End of Bootstrap responsive table
            
    // To Do 4 (Practical 4): 
    // Display the subtotal at the end of the shopping cart
    echo "<p style='text-align:right; font-size:20px'>
        Subtotal = S$". number_format($subTotal, 2);
    $_SESSION["SubTotal"] = round($subTotal, 2);
    // To Do 7 (Practical 5):
    // Add PayPal Checkout button on the shopping cart page
    echo "<form method='post' action='checkoutProcess.php'>";
    echo "<input type='image' style='float:right;'
                src ='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
    echo "</form></p>";	
}	
else {
    echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
$conn->close(); // Close database connection
}
else {
echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
*/
include("footer.php"); // Include the Page Layout footer

?>
