<?php 
    session_start();
    // Process after user click the submit button
    if (isset($_POST["currentPassword"])) {
        $shopperId = $_SESSION["ShopperID"];
        $currentPassword = $_POST["currentPassword"];
        $newPassword = $_POST["newPassword"];
        // Retrieve shopper record based on e-mail address
        include_once("mysql_conn.php");
        $qry = "SELECT * FROM Shopper WHERE ShopperID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $shopperId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            $row = $result->fetch_array();
            $hash_pwd = $row["Password"];
            if((password_verify($currentPassword, $hash_pwd) == true) || ($currentPassword == $hash_pwd)){
                $hash_pwd = password_hash($newPassword, PASSWORD_DEFAULT);
                $qry = "UPDATE Shopper SET Password=? WHERE ShopperID=?";
                $stmt = $conn->prepare($qry);
                $stmt->bind_param("si", $hash_pwd, $shopperId);
                $stmt->execute();
                $stmt->close();
                echo "Password Changed";
            }
            else{
                echo "Current password is incorrect.";
            }
            
            
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