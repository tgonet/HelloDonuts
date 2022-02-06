<?php 
    session_start();

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
            $row = $result->fetch_array();
            if($row["ShopperID"] == $_SESSION["ShopperID"]){
                return true;
            }
            else{
                return false;
            }
        }
        return true;
    }

    if(isset($_POST["email"])) {
        $name = $_POST["name"];
        $_SESSION["ShopperName"] = $name;
        $DOB = $_POST["bday"];
        $address = $_POST["address"];
        $country = $_POST["country"];
        $phone = "(65) ".$_POST["phone"];
        $email = $_POST["email"];
    
        if(checkUnique($email) == false){
            echo "This email is taken. Please use another email.";
        }
        else{
            include("mysql_conn.php");
            $qry = "UPDATE Shopper SET Name=?, BirthDate=?, Address=?, Country=?, Phone=?, Email=? WHERE ShopperID=?";
            $stmt1 = $conn->prepare($qry);
            $stmt1->bind_param("sssssss", $name, $DOB, $address, $country, $phone, $email, $_SESSION["ShopperID"]);
            $stmt1->execute();
            echo "success";
        }
    }
   
?>