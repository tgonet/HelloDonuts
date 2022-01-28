<?php
    include_once("mysql_conn.php"); 
    $qry2 = "SELECT DISTINCT * FROM ranking r INNER JOIN Shopper s ON r.ShopperID = s.ShopperID where productid=?";
    $stmt = $conn->prepare($qry2);
    $stmt->bind_param("i", $_SESSION["pid"]); 
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    echo "<div class='row' style='margin: 3em 4em 3em 4em;'>";
    echo "<div class='col-sm-10' style='padding-left:0'>";
    echo "<h3 style='margin-top:50px; color: #63200D; width: 100%;' >Comments</h3>";    
    echo "</div>";
    echo "<div class='col-sm-2'>";
    echo "<form action='rating.php' style='width:100%;margin:0 0;'>";
    echo "<button align='right' type='rate'>RATE!</button>";
    echo "</form>";
    echo "</div>";
    while($row = $result->fetch_array()){
        $rating = round($row["RankInput"]);
        echo "<div class='background' style='width:100%; padding:20px 0 20px 0;margin-bottom:2.5em !important;'>";
        echo "<div style='margin-left:20px;'>";
        echo "<p style='font-size:20px;font-weight:600;margin-bottom:0.7rem;'>$row[Name]</p>";
        for ($x = 1; $x <= $rating; $x++) {
            echo "<img src='Images/rating_show.png' style='margin-right:10px;'>";
          }
        for ($x = 1; $x <= 5-$rating; $x++) {
            echo "<img src='Images/rating_unshown.png' style='margin-right:10px;'>";
        }
        echo "<p style='margin-bottom:0px; margin-top:0.7rem;font-size:16px;'>$row[Comment]</p>";
        echo "</div>";
        echo "</div>";
    }

    echo "</div>";
?>      
<div>
</div>

<style>
    button[type="rate"]{
    padding: 6px 30px 6px 30px;
    display: block; 
    margin-left: 100px;
    border-radius: 30px  !important;
    background: #CAF0F8;
    border: none;
    font-weight: 900;
    font-size: 22px;
    color: #63200D;
}
</style>