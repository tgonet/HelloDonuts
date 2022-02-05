<?php
session_start();
include_once("mysql_conn.php");
include("header.php");

$qry = "SELECT r.ProductID, Avg(r.RankInput) as AveRank, p.ProductImage, p.ProductTitle FROM Ranking r INNER JOIN Product p ON r.ProductID = p.ProductID GROUP BY r.ProductID ORDER BY AveRank ASC";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
echo "<div class='background' style='margin-top:30px !important;'>";
foreach($result as $i){
    $img = "./Images/products/$i[ProductImage]";
    $product = "productDetails.php?pid=$i[ProductID]";
    $rating = round($i["AveRank"]);
    echo "<div class='row' style='margin:0 15px 0 30px;'>";
        echo "<div class='col-sm-2' style='padding:15px;'>";
            echo "<img style='border-radius: 10%; width: 14em; height: 14em; object-fit: cover; margin-bottom: 30px;' src='$img' />";
        echo "</div>";
        echo "<div class='col-sm-3' style='padding:0;'>";
            echo "<h4 style ='font-size: 30px' ><a style ='color: #63200D; text-decoration: none' href=$product>$i[ProductTitle]</a></h4>";
            //echo "<p>$i[ProductTitle]</p>";
            for ($x = 1; $x <= $rating; $x++) {
                echo "<img src='Images/rating_show.png' style='margin-right:10px;'>";
            }
            for ($x = 1; $x <= 5-$rating; $x++) {
                echo "<img src='Images/rating_unshown.png' style='margin-right:10px;'>";
            }
        echo "</div>";
    echo "</div>";
}
echo "</div>";

include("footer.php");
?>