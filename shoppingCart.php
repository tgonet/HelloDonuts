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
		echo "<a class='mybreadcrumb-content complete' href=''>";
		echo "<span class='mybreadcrumb-circle complete'>1</span> My Tray";
		echo "</a>";
        echo "</div>";
        echo "<div class='col-sm-3'>";
		echo "<a class='mybreadcrumb-content' href=''>";
		echo "<span class='mybreadcrumb-circle'>2</span> Delivery Mode";
		echo "</a>";
        echo "</div>";
        echo "<div class='col-sm-3'>";
		echo "<a class='mybreadcrumb-content' href=''>";
		echo "<span class='mybreadcrumb-circle'>3</span> Review Order";
		echo "</a>";
        echo "</div>";
        echo "<div class='col-sm-3'>";
		echo "<a class='mybreadcrumb-content' href=''>";
		echo "<span class='mybreadcrumb-circle'>4</span> Make Payment";
		echo "</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

		// the page header of shopping cart page
		echo "<h1 class='page-title'>My Tray</h1>"; 

		// the header row of shopping cart page
		echo "<div class='tray-background'>";
		echo "<div class='table-responsive tray-table' >"; // Bootstrap responsive table
		echo "<table class='table'>"; // Start of table
		echo "<thead class='brown-text'>"; // start of table's header section
		echo "<tr>"; // start of header row
		echo "<th colspan='2'>Donuts</th>";
		// echo "<th>&nbsp;</th>";
		echo "<th>Quantity</th>";
		echo "<th>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; // end of header row
		echo "</thead>"; // end of table's header section

		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"] = array();	
		// Declare a variables
		$subtotal = 0; 
		$discountSubtotal = 0; 
		$totalItems = 0;
		
		// Display the shopping cart content
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			// get image of product
			$qry1 = "SELECT ProductImage, Offered, OfferStartDate, OfferEndDate, OfferedPrice 
					 FROM Product WHERE ProductID = ?";
			$stmt1 = $conn->prepare($qry1);
			$stmt1->bind_param("i", $row["ProductID"]); 
			$stmt1->execute();
			$row1 = $stmt1->get_result()->fetch_array();
			$stmt1->close();
			
			// image
			$img = "./Images/products/$row1[ProductImage]";
			echo "<tr>";
			echo "<td style='width: 8em;'><img src=$img class='donut-img'></td>"; 
			
			// name, unit price, offer
			$formattedPrice = number_format($row["Price"], 2);
			$discount = 0;
			echo "<td style='text-align: left; vertical-align: middle;'>";
			echo "<span class='tray-donut-name'>$row[Name]</span></br>";
			if ($row1["Offered"] == 1 && date("Y-m-d") < $row1["OfferEndDate"] 
			&& date("Y-m-d") > $row1["OfferStartDate"]) {
				$discount = $row1["OfferedPrice"];
				$formattedDiscount = number_format($row1["OfferedPrice"], 2);
				echo "<span style='font-weight: 600;'>Unit Price: </span>";
				echo "<span style='font-weight: 600; text-decoration: line-through;'>$$formattedPrice</span>";
				echo "<span style='font-weight: 700; font-size: 16px; color: #DD8331;'> $$formattedDiscount</span>";
			} else {
				echo "<span style='font-weight: 600;'>Unit Price: $$formattedPrice</span></br>";
			}
			echo "</td>"; 
			
			// column for update quantity of purchase
			echo "<td style='vertical-align: middle;'>"; 
			echo "<form id='tray-quantity' action='cartFunctions.php' method='post'>";
			echo "<div class='input-group plus-minus-input'>"; 
			echo "<div class='input-group-button'>"; 
			echo "<button type='button' class='button-icon minus' data-quantity='minus' data-field='$row[ProductID]'>"; 
			echo "<i class='fas fa-minus plus-minus-btn'></i>"; 
			echo "</button>"; 
			echo "</div>"; 
			echo "<input class='qty-field' type='number' name='quantity' id='$row[ProductID]' value='$row[Quantity]' min=0 max=100 onChange='this.form.submit()'>"; 
			echo "<div class='input-group-button'>"; 
			echo "<button type='button' class='button-icon plus' data-quantity='plus' data-field='$row[ProductID]'>"; 
			echo "<i class='fas fa-plus plus-minus-btn'></i>"; 
			echo "</button>"; 
			echo "</div>"; 
			echo "</div>"; 
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";

			// total for each donut
			if ($row1["Offered"] == 1 && date("Y-m-d") < $row1["OfferEndDate"] 
			&& date("Y-m-d") > $row1["OfferStartDate"]) {
				$formattedTotal = number_format($row["Quantity"] * $row1["OfferedPrice"], 2);
				echo "<td style='vertical-align: middle; font-weight: 600;'>$formattedTotal</td>";
			} else {
				$formattedTotal = number_format($row["Total"], 2);
				echo "<td style='vertical-align: middle; font-weight: 600;'>$formattedTotal</td>";
			}
			
			// remove item from shopping cart
			echo "<td style='vertical-align: middle;'>"; 
			echo "<form id='tray-delete' action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<button type='button' class='delete-btn' onClick='this.form.submit()'>"; 
			echo "<i class='far fa-trash-alt'></i>";
			echo "</button>"; 
			echo "</form>";
			echo "</td>";
			echo "</tr>";
			
			// Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][] = array("image" => $row1["ProductImage"],
										 "productId" => $row["ProductID"],
										 "name" => $row["Name"],
										 "price" => $row["Price"],
										 "offeredPrice" => $discount,
										 "quantity" => $row["Quantity"],
										 "total" => $formattedTotal);
			
			// Accumulate the running sub-total
			$subtotal += $row["Total"];
			$discountSubtotal += $formattedTotal; // with discount
			$totalItems += $row["Quantity"];
		}
		// Display the subtotal at the end of the shopping cart
		$formattedDiscountSubtotal = number_format($discountSubtotal, 2); // with discount
		$formattedSubtotal = number_format($subtotal, 2); // with discount
		echo "<tr class='tray-divider'>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td class='tray-subtotal'>Subtotal</td>";
		echo "<td class='tray-subtotal'>"; 
		echo" <span style='text-decoration: line-through; font-weight: 600; font-size: 18px; color: #bfa288;'>$formattedSubtotal</span>"; 
		echo " $formattedDiscountSubtotal</td>";
		echo "<td></td>";
		echo "</tr>";
		$_SESSION["SubTotal"] = round($subtotal, 2);
		$_SESSION["DiscountSubTotal"] = round($discountSubtotal, 2);

		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
		echo "</div>"; // End of background
		
		// items in tray
		echo "<div class='row tray-count'>";
		echo "<div class='col-sm-6'>";
		echo "<p>$totalItems item(s) in tray</p>";
		echo "</div>";
		echo "<div class='col-sm-6'>";
		echo "<a href='deliveryMode.php' class='next-page-btn'>Proceed to Checkout</a>";
		echo "</div>";
		echo "</div>";

		// TODO: button to next page
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
$('.button-icon').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    quantity  = $(this).attr('data-quantity');
    var input = $("input[id='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(quantity == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(quantity == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.qty-field').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.qty-field').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    id = $(this).attr('id');
    if(valueCurrent >= minValue) {
        $(".button-icon[data-quantity='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".button-icon[data-quantity='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
    
});
</script>

<?php
include("footer.php"); // Include the Page Layout footer
?>
