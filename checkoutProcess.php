<?php
session_start();
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php"); 
if (!isset($_SESSION["checkOutSuccess"]))
{
	$_SESSION["checkOutSuccess"] = true;
	$_SESSION["PaymentMethod"] = $_POST["paymentMethod"];
	$_SESSION["Message"] = NULL;
	if(!empty($_POST["Message"]))
	{
		$_SESSION["Message"] = $_POST["Message"];
	}
}

if (isset($_SESSION["checkOutSuccess"])){
	// Check to ensure each product item saved in the associative
	// array is not out of stock
	$outOfStock = array();
	foreach($_SESSION['Items'] as $key=>$item) {
		$qry = "SELECT Quantity FROM Product
				WHERE ProductID = ?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $item["productId"]);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_array();
		$stmt->close();
		if ($result["Quantity"] < $item["quantity"]) {
			array_push($outOfStock, $item["name"]);
		}
	}
	if (!empty($outOfStock))
	{	
		$items = implode(', ', $outOfStock);
		echo "<script>
			alert('Sorry, $items is/are out of stock. You will be redirected to shopping cart to edit your order.');
			document.location = 'shoppingCart.php';
		</script>";
	}

	else{
		
		$paypal_data = '';
		// Get all items from the shopping cart, concatenate to the variable $paypal_data
		// $_SESSION['Items'] is an associative array
		foreach($_SESSION['Items'] as $key=>$item) {
			$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
			if ($item["offeredPrice"] == 0)
			{
				$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
			}
			else
			{
				$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["offeredPrice"]);
			}
			$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
			$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'= '.urlencode($item["productId"]);
		}
		
		//Data to be sent to PayPal
		$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&PAYMENTACTION=Sale'.
				'&ALLOWNOTE=1'.
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["DiscountSubTotal"] +
													$_SESSION["Tax"] + 
													$_SESSION["DeliveryCharge"] +
													$_SESSION["DeliveryDiscount"] ).
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["DiscountSubTotal"]). 
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["DeliveryCharge"]). 
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($_SESSION["DeliveryDiscount"]).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]). 	
				'&BRANDNAME='.urlencode("Hello Donuts").
				$paypal_data.				
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL);	
		
			
		//We need to execute the "SetExpressCheckOut" method to obtain paypal token
		$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
										$PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
			
		//Respond according to message we receive from Paypal
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
			if($PayPalMode=='sandbox')
				$paypalmode = '.sandbox';
			else
				$paypalmode = '';
					
			//Redirect user to PayPal store with Token received.
			$paypalurl ='https://www'.$paypalmode. 
						'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
						$httpParsedResponseAr["TOKEN"].'';
			header('Location: '.$paypalurl);
		}
		else {
			include("header.php"); // Include the Page Layout header
			//Show error message
			echo "<h2 style='color:red; text-align:center; margin-top: 20px !important;'>Checkout Failed</h2>";
			echo "<div style='font-size: 18px;color: #63200d;text-align:center; width:70%; margin:auto;'>We apologize for the inconvenience, 
			<span style='color:red'>the online checkout system is down.</span></br>
			Do reach us at <a href='tel:+65 91234567'>+65 91234567</a> or 
			email us at <a href='mailto:hello@donut.com'>hello@donut.com</a> for orders or enquires. 
			</br>We thank you for your understanding.</div>'";
			echo "<script>
			console.log('SetExpressCheckOut failed:".urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."');
			</script>";
		}


		//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
		if(isset($_GET["token"]) && isset($_GET["PayerID"])) {	
			//we will be using these two variables to execute the "DoExpressCheckoutPayment"
			//Note: we haven't received any payment yet.
			$token = $_GET["token"];
			$playerid = $_GET["PayerID"];
			$paypal_data = '';
			
			// Get all items from the shopping cart, concatenate to the variable $paypal_data (called again as it is not stored in session)
			// $_SESSION['Items'] is an associative array
			foreach($_SESSION['Items'] as $key=>$item) 
			{
				$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
				if ($item["offeredPrice"] == 0)
				{
					$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
				}
				else
				{
					$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["offeredPrice"]);
				}
				$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
				$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
			}
			
			//Data to be sent to PayPal
			$padata = '&TOKEN='.urlencode($token).
					'&PAYERID='.urlencode($playerid).
					'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
					$paypal_data.	
					'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["DiscountSubTotal"]).
					'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["DeliveryCharge"]).
					'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($_SESSION["DeliveryDiscount"]).
					'&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]).
					'&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["DiscountSubTotal"] + 
														$_SESSION["Tax"] + 
														$_SESSION["DeliveryCharge"]+
														$_SESSION["DeliveryDiscount"]).
					'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
			
			//We need to execute the "DoExpressCheckoutPayment" at this point 
			//to receive payment from user.
			$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
											$PayPalApiUsername, $PayPalApiPassword, 
											$PayPalApiSignature, $PayPalMode);
			
			//Check if everything went ok..
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
			"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
			{
				// Update stock inventory in product table 
				//                after successful checkout

				$qry = "SELECT ProductID, Quantity FROM ShopCartItem 
						WHERE ShopCartID = ?";
				$stmt = $conn->prepare($qry);
				$stmt->bind_param("i", $_SESSION["Cart"]);
				$stmt->execute();
				$items = $stmt->get_result();
				$stmt->close();

				while ($item = $items->fetch_array()) {
					$qry = "UPDATE Product SET Quantity = Quantity - ?
							WHERE ProductID = ?";
					$stmt = $conn->prepare($qry);
					$stmt->bind_param("ii", $item["Quantity"], $item["ProductID"]);
					$stmt->execute();
					$stmt->close();
				}

				$shipCharge = $_SESSION["DeliveryCharge"] + $_SESSION["DeliveryDiscount"];
				$discount = $_SESSION["SubTotal"]-$_SESSION["DiscountSubTotal"];
				// Update shopcart table, close the shopping cart (OrderPlaced=1)
				$qry = "UPDATE shopcart SET OrderPlaced=1, Quantity=?,
						SubTotal=?, ShipCharge=?, Tax=?, Total=?, Discount=?
						WHERE ShopCartID=?";
				$stmt = $conn->prepare($qry);
				// "i" - integer, "d" - double
				$stmt->bind_param("iddddii", $_SESSION["TotalItems"],
								$_SESSION["SubTotal"], $shipCharge,
								$_SESSION["Tax"], $_SESSION["Total"],$discount,
								$_SESSION["Cart"]);
				$stmt->execute();
				$stmt->close();
				
				//We need to execute the "GetTransactionDetails" API Call at this point 
				//to get customer details
				$transactionID = urlencode(
								$httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
				$nvpStr = "&TRANSACTIONID=".$transactionID;
				$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
												$PayPalApiUsername, $PayPalApiPassword, 
												$PayPalApiSignature, $PayPalMode);

				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
				"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
				{
					//gennerate order entry and feed back orderID information
					//You may have more information for the generated order entry 
					//if you set those information in the PayPal test accounts.
					
					$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"])); // this is because some names contains "'", which closes the string in SQL
					
					$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
					if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
						$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
					$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
					' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
						
					$ShipCountry = urldecode(
								$httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
						
					$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			
					
					// To Do 3: Insert an Order record with shipping information
					//          Get the Order ID and save it in session variable.
					
					$qry = "INSERT INTO orderdata (ShipName, ShipAddress, ShipCountry,
													ShipEmail, DeliveryDate, DeliveryTime, 
													DeliveryMode, Message, ShopCartID)
							VALUES(?,?,?,?,?,?,?,?,?)";
					$stmt = $conn->prepare($qry);
					// "i" - integer, "s" - string
					$stmt->bind_param("ssssssssi", $ShipName, $ShipAddress, $ShipCountry, 
												$ShipEmail, $_SESSION["DeliveryDate"], $_SESSION["DeliveryTime"], 
												$_SESSION["DeliveryMode"], $_SESSION["Message"], $_SESSION["Cart"]);
					$stmt->execute();
					$stmt->close();
					$qry = "SELECT LAST_INSERT_ID() AS OrderID";
					$result = $conn->query($qry);
					$row = $result->fetch_array();
					$_SESSION["OrderID"] = $row["OrderID"];
					// End of To Do 3
						
					$conn->close();
						
					// To Do 4A: Reset the "Number of Items in Cart" session variable to zero.
					$_SESSION["NumCartItem"] = 0;
					$_SESSION["TotalItems"] = 0;
					
					// To Do 4B: Clear the session variable that contains Shopping Cart ID.
					unset($_SESSION["Cart"]);
					
					// To Do 4C: Redirect shopper to the order confirmed page.
					header("Location: orderConfirmed.php");
					exit;

				} 
				else 
				{
					include("header.php"); // Include the Page Layout header
					echo "<h2 style='color:red; text-align:center; margin-top: 20px !important;'>Checkout Failed - System Down</h2>";
					echo "<div style='font-size: 18px;color: #63200d;text-align:center; width:70%; margin:auto;'>We apologize for the inconvenience, 
					<span style='color:red'>the online checkout system is down.</span></br>
					Do reach us at <a href='tel:+65 91234567'>+65 91234567</a> or 
					email us at <a href='mailto:hello@donut.com'>hello@donut.com</a> for orders or enquires. 
					</br>We thank you for your understanding.</div>'";
					echo "<script>
					console.log('GetTransactionDetails failed:".urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."');
					</script>";
					$conn->close();
				}
			}
			else {
				include("header.php"); // Include the Page Layout header
				echo "<h2 style='color:red; text-align:center; margin-top: 20px !important;'>Checkout Failed - System Down</h2>";
				echo "<div style='font-size: 18px;color: #63200d;text-align:center; width:70%; margin:auto;'>We apologize for the inconvenience, 
				<span style='color:red'>the online checkout system is down.</span></br>
				Do reach us at <a href='tel:+65 91234567'>+65 91234567</a> or 
				email us at <a href='mailto:hello@donut.com'>hello@donut.com</a> for orders or enquires. 
				</br>We thank you for your understanding.</div>'";
				echo "<script>
				console.log('DoExpressCheckoutPayment failed:".urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."');
				</script>";
			}
		}
	}
}

include("footer.php"); // Include the Page Layout footer
?>