<?php 
    session_start();

    include_once("mysql_conn.php");
    $comment = $_POST["comment"];
    $rating = $_POST["rating"];
    
    echo $_SESSION["ShopperID"];
    $qry = "INSERT INTO Ranking (ShopperID, ProductID, RankInput, Comment)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($qry);
    echo $rating;
    $stmt->bind_param("iiss", $_SESSION["ShopperID"],$_SESSION["pid"], $rating, $comment);
    $stmt->execute();
    $stmt->close();
    $conn->close();
?>