<?php 
session_start(); // Detect thye current session

function checkUnique($email){
    include_once("mysql_conn.php");
    $qry = "SELECT * FROM Shopper WHERE Email = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if($result->num_rows > 0){
        return false;
    }
    return true;
}

// Read the data input from the previous page
$name = $_POST["name"];
$DOB = $_POST["bday"];
$postalCode = $_POST["postalCode"];
$address = $_POST["address"];
$unitNo = $_POST["unitNo"];
$country = $_POST["country"];
$phone = "(65) ".$_SESSION["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$Pwd_Questiion = $_POST["SecQns"];
$Pwd_Answer = $_POST["SecAns"];
$finalAddress = $address." ".$unitNo;

if(checkUnique($email) == false){
    echo "This email is taken. Please use another email or login instead.";
}
else{
    include("mysql_conn.php");
    
    // Create a poassword hash using the default bcrypt algorithm
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Define thge INSERT SQL statement
    $qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($qry);
    // "ssssss" - 6 string parameters
    $stmt->bind_param("sssssssss", $name, $DOB, $finalAddress, $country, $phone, $email, $password, $Pwd_Questiion, $Pwd_Answer);

    if ($stmt->execute()) { // SQL statement executed successfully
        // Retrieve the Shopper ID assigned to the new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry); // Execute the SQL and get the returned result
        while ($row = $result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }

        //Successful message and Shopper ID
        echo "Registration successful! Your ShopperID is $_SESSION[ShopperID]. \nClick 'OK' to start shopping with us.";
        // Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;
        unset($_SESSION["phone"]);
    }
    else{  // Error message
        echo "Error in inserting record";
    }

    // Release the resource allocated for prepared statement
    $stmt->close();
    // Close the database connection
    $conn->close();
    exit;
}
?>