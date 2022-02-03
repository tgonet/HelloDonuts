<?php
    session_start(); 
    // Process after user click the submit button
    if (isset($_POST["eMail"])) {
        $eMail = $_POST["eMail"];
        $secQns = $_POST["SecQns"];
        $secAns = $_POST["SecAns"];
        // Retrieve shopper record based on e-mail address
        include_once("mysql_conn.php");
        $qry = "SELECT * FROM Shopper WHERE Email=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("s", $eMail);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            // Check Security Question
            $row = $result->fetch_array();
            if($row["PwdQuestion"] != $secQns || $row["PwdAnswer"] != $secAns){
                //echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
                echo "Please check your security question and answer";
            }
            else{
                // To Do 1: Update the default new password to shopper"s account
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
            
                	// To Do 2: e-Mail the new password to user
                	include("myMail.php");
                	// The "Send To" should be the email address indicated 
                	// by shopper, i.e $eMail. In this case, use a testing email
                	// address as the shopper's email address in our database
                	// may not be a valid account.
                	$to = "awjoeytest@gmail.com";
                	$from = "awjoeytest@gmail.com";
                	$from_name = "HelloDonuts";
                	$subject = "helloDonuts Login Password"; // email title
                	$body = "<span style='color:black; font-size:12px'>
                			Your new password is <span style='font-weight:bold'>
                			$new_pwd</span>.<br />
                			Do change this default password.</span>";
                			// Initiate the email sending process
                			if(smtpmailer($to, $from, $from_name, $subject, $body)){
                				echo "<p>Your new password is sent to:
                					<span style='font-weight:bold'>$to</span>.</p>";
                			}
                			else{
                				echo "<p><span style='color:red;'>
                					Mailer Error: " .$error. "</span></p>";
                			}
            }
        }
        else{
            echo "Invalid User";
        }
        $conn->close();
    }
?>