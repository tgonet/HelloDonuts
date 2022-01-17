<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a cenrally located container -->
<div style="margin: 7em 0 7em 0; padding: 0">
	<div style="width:80%; margin:auto;" class="background">
		<form method="post" style="margin: auto">
			<h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Forget Password</h3>
			<div class="form-group row" style="margin-bottom:40px;">
				<label class="col-sm-3 col-form-label" for="eMail">
				Email Address:</label>
				<div class="col-sm-9">
					<input class="form-control textfield" name="eMail" id="eMail"
						type="email" required />
				</div>
			</div>
			<div class="form-group row" style="margin-bottom:40px;">
				<label for="SecQns" class="col-sm-3 col-form-label">Security Question: </label>
				<div class="col-sm-9">
					<select id="SecQns" name="SecQns" class="textfield form-control minimal" required>
						<option value="" disabled selected>Please choose a security question only you know the answer for</option>
						<option value="Which polytechnic?">Which polytechnic?</option>
                        <option value="Wife's name?">Wife's name?</option>
                        <option value="How many brothers and sisters?">How many brothers and sisters?</option>
					</select>
				</div>
			</div>
			<div class="form-group row" style="margin-bottom:40px;">
				<label for="SecAns" class="col-sm-3 col-form-label">Security Answer: </label>
				<div class="col-sm-9">
					<input class="form-control textfield" type="test" name="SecAns" id="SecAns" placeholder="Please enter your answer" required/>
				</div>
			</div>
			<button type="submit" class="center" style="padding: 10px 90px 10px 90px">Register</button>
		</form>
	</div>
</div>

<?php 
// Process after user click the submit button
if (isset($_POST["eMail"])) {
	// Read email address entered by user
	$eMail = $_POST["eMail"];
	// Retrieve shopper record based on e-mail address
	include_once("mysql_conn.php");
	$qry = "SELECT * FROM Shopper WHERE Email=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $eMail); 	// "s" - string 
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	if ($result->num_rows > 0) {
		// To Do 1: Update the default new password to shopper"s account
		$row = $result->fetch_array();
		$shopperId = $row["ShopperID"];
		$new_pwd = "password"; // Default password
		// Hash the default password
		$hash_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
		$qry = "UPDATE Shopper SET Password=? WHERE ShopperID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("si", $hash_pwd, $shopperId);
		$stmt->execute();
		$stmt->close();
		// End of To Do 1
		
	// 	// To Do 2: e-Mail the new password to user
	// 	include("myMail.php");
	// 	// The "Send To" should be the email address indicated 
	// 	// by shopper, i.e $eMail. In this case, use a testing email
	// 	// address as the shopper's email address in our database
	// 	// may not be a valid account.
	// 	$to = "tanmingzhe.99@gmail.com";
	// 	$from = "ecadtest8@gmail.com";
	// 	$from_name = "Mamaya e-BookStore";
	// 	$subject = "Mamay e-BookStore Login Password"; // email title
	// 	$body = "<span style='color:black; font-size:12px'>
	// 			Your new password is <span style='font-weight:bold'>
	// 			$new_pwd</span>.<br />
	// 			Do change this default password.</span>";
	// 			// Initiate the email sending process
	// 			if(smtpmailer($to, $from, $from_name, $subject, $body)){
	// 				echo "<p>Your new password is sent to:
	// 					<span style='font-weight:bold'>$to</span>.</p>";
	// 			}
	// 			else{
	// 				echo "<p><span style='color:red;'>
	// 					Mailer Error: " .$error. "</span></p>";
	// 			}
	// 	// End of To Do 2
	// }
	// else {
	// 	echo "<p><span style='color:red;'>
	// 	      Wrong E-mail address!</span></p>";
	}
	$conn->close();
}
?>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>