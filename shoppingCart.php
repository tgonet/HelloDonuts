<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

echo "<div class='row' style='padding:20px 0 0 20px'>";
echo "<div class='col-sm-12'>";
echo "<a class='back-link' href='category.php'>";
echo "<i class='fas fa-chevron-left back-btn'></i> Back to shopping";
echo "</a>";
echo "</div>";
echo "</div>";

// Start a container
echo "<div id='myShopCart' style='width:100%; margin:auto; text-align:center'>"; 


// the page header of shopping cart page
echo "<h1 class='page-title' style='text-align:center'>My Tray</h1>"; 
// items in tray
echo "<div class='tray-count'>";
echo "<p>item(s) in tray</p>";
echo "</div>";
// the header row of shopping cart page
echo "<div class='tray-background'>";
echo "<div class='table-responsive' >"; // Bootstrap responsive table
echo "<table class='table table-hover'>"; // Start of table
echo "<thead class='cart-header'>"; // start of table's header section
echo "<tr>"; // start of header row
echo "<th>Donuts</th>";
echo "<th>&nbsp;</th>";
echo "<th>Quantity</th>";
echo "<th>Total (S$)</th>";
echo "<th>&nbsp;</th>";
echo "</tr>"; // end of header row
echo "</thead>"; // end of table's header section

echo "<tbody>";
echo "<tr>";
echo "<td><img src='Images/Products/Donut_summersnow.png' alt='Summer Snow Donut' class='donut-img'></td>"; // img
echo "<td style='text-align: left; vertical-align: middle;'>
<span class='tray-donut-name'>Summer Snow Donut</span></br>
<span class='tray-donut-price'>Unit Price: </span><span>$1.50</span></br>
<span class='tray-donut-offer'>On Offer</span>
</td>"; // name, unit price, offer
echo "<td style='vertical-align: middle;'>
<form action='cartFunctions.php' method='post'>
<div class='input-group plus-minus-input'>
  <div class='input-group-button'>
    <button type='button' class='button-icon minus' data-quantity='minus' data-field='quantity'>
      <i class='fas fa-minus plus-minus-btn'></i>
    </button>
  </div>
  <input class='qty-field' type='number' name='quantity' id='quantity' value='0' min=0 max=10>
  <div class='input-group-button'>
    <button type='button' class='button-icon plus' data-quantity='plus' data-field='quantity'>
      <i class='fas fa-plus plus-minus-btn'></i>
    </button>
  </div>
</div>
<input type='hidden' name='action' value='update' />
</form>
</td>"; // quantity
// <input type='hidden' name='product_id' value='$row[ProductID]' />
echo "<td style='vertical-align: middle;'>1.50</td>"; // total
echo "<td style='vertical-align: middle;'><i class='far fa-trash-alt'></i></td>"; // delete, TODO: add form
echo "</tr>";
echo "<tr class='tray-divider'>";
echo "<td></td>";
echo "<td></td>";
echo "<td class='tray-subtotal'>Subtotal</td>";
echo "<td class='tray-subtotal'>1.50</td>";
echo "<td></td>";
echo "</tr>";
echo "</tbody>"; // End of table's body section
echo "</table>"; // End of table
echo "</div>";
echo "</div>"; // close container?

if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT *, (Price * Quantity) AS Total
			FROM ShopCartItem WHERE ShopCartID = ?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]); // i - int
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		// Format and display 
        // breadcrumb
        echo "<div class='mybreadcrumb'>";
        echo "<div class='row'>";
        echo "<div class='col-sm-3'>";
        echo "<div class='circle mybreadcrumb-circle complete'>1</div>";
        echo "<div class='mybreadcrumb-content complete'>My Tray</div>";
        echo "</div>";
        echo "<div class='col-sm-3'>";
        echo "<div class='circle mybreadcrumb-circle'>2</div>";
        echo "<div class='mybreadcrumb-content'>Delivery Mode</div>";
        echo "</div>";
        echo "<div class='col-sm-3'>";
        echo "<div class='circle mybreadcrumb-circle'>3</div>";
        echo "<div class='mybreadcrumb-content'>Review</div>";
        echo "</div>";
        echo "<div class='col-sm-3'>";
        echo "<div class='mybreadcrumb-content'>Payment</div>";
        echo "<div class='circle mybreadcrumb-circle'>4</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // items in tray
        echo "<span>item(s) in tray</span>";

		// the page header and header row of shopping cart page
		echo "<h1 class='page-title' style='text-align:center'>My Tray</h1>"; 
        echo "<div class='background'>";
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class='cart-header'>"; // start of table's header section
		echo "<tr>"; // start of header row
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; // end of header row
		echo "</thead>"; // end of table's header section

		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"] = array();	

		// To Do 3 (Practical 4): 
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]<br />";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			echo "<td>$formattedPrice</td>";
			echo "<td>"; // column for update quantity of purchase
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++) { // to populate drop-down list from 1 to 10
				if ($i == $row["Quantity"])
					// select drop-down list item with value same as the quantity of purchase
					$selected = "selected";
				else
					$selected = ""; // no specific item selected
				echo "<option value='$i' $selected>$i</option>";
			} 
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			echo "<td>$formattedTotal</td>";
			echo "<td>"; // column for remove item from shopping cart
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='images/trash-can.png' title='Remove Item' />";
			echo "</form>";
			echo "</td>";
			echo "</tr>";

			// To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][] = array("productId" => $row["ProductID"],
										 "name" => $row["Name"],
										 "price" => $row["Price"],
										 "quantity" => $row["Quantity"]);	

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
		echo "<input type='image' style='float: right;' src='https://paypal.com/en_US/i/btn/btn_xpressCheckout.gif' alt='PayPal Checkout'>";
		echo "</form></p>";
	}
	else {
		echo "<div class='empty-tray-msg'>";
        echo "<h1>Empty tray!</h1>";
        echo "<p>Pick some donuts before you come back</p>";
        echo "</div>";
	}
	$conn->close(); // Close database connection
}
else {
    echo "<div class='empty-tray-msg'>";
	echo "<h1>Empty tray!</h1>";
    echo "<p>Pick some donuts before you come back</p>";
    echo "</div>";
}
echo "</div>"; // End of container
?>

<script>
jQuery(document).ready(function(){
    // This button will increment the value
    $('[data-quantity="plus"]').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('data-field');
        // Get its current value
        var currentVal = parseInt($('input[name=quantity]').val());
        // If is not undefined
        if (!isNaN(currentVal) && currentVal < 10) {
            // Increment
            $('input[name=quantity]').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name=quantity]').val(currentVal);
        }
    });
    // This button will decrement the value till 0
    $('[data-quantity="minus"]').click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('data-field');
        // Get its current value
        var currentVal = parseInt($('input[name=quantity]').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name=quantity]').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name=quantity]').val(currentVal);
        }
    });
});
</script>

<?php
include("footer.php"); // Include the Page Layout footer
?>
