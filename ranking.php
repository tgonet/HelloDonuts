<?php
session_start();
include_once("mysql_conn.php");
include("header.php");
$count = 1;

$qry = "SELECT r.ProductID, Avg(r.RankInput) as AveRank, p.ProductImage, p.ProductTitle FROM Ranking r INNER JOIN Product p ON r.ProductID = p.ProductID GROUP BY r.ProductID ORDER BY AveRank ASC";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
echo "<div class='background' style='margin-top:30px !important;'>";
echo "<h3 align='center'>Best Sellers!</h3>";
echo "<table>";
foreach($result as $i){
    $img = "./Images/products/$i[ProductImage]";
    $product = "productDetails.php?pid=$i[ProductID]";
    $rating = round($i["AveRank"]);
    echo "<tr>";
    echo "<div class='row' style='margin:0 15px 0 30px;'>";
    echo "<td>";
            echo "<h4 id='rank' style ='color: #63200D;margin-left:30px;'>$count.</h4>";
        echo "</td>";
        echo "<td style='padding:15px;'>";
            echo "<img id='imgs' style='border-radius: 10%; width: 14em; height: 14em; object-fit: cover; margin-bottom: 30px;' src='$img' />";
        echo "</td>";
        echo "<td>";
            echo "<h4 style ='font-size: 30px' ><a style ='color: #63200D; text-decoration: none' href=$product>$i[ProductTitle]</a></h4>";
            //echo "<p>$i[ProductTitle]</p>";
            for ($x = 1; $x <= $rating; $x++) {
                echo "<img src='Images/rating_show.png' style='margin-right:10px;'>";
            }
            for ($x = 1; $x <= 5-$rating; $x++) {
                echo "<img src='Images/rating_unshown.png' style='margin-right:10px;'>";
            }
        echo "<td>";
        
    echo "</div>";
    echo "<tr>";
    $count +=1; 
}
echo "</table>";
echo "</div>";

include("footer.php");
?>

<style>
    tr{
        margin-bottom: 10px;
    }
    td{
        padding: 0 10px 0 10px;
    }
    @media (max-width: 890px) {
        #imgs{
            width:3em !important;
            height:3em !important;
            margin-bottom:0 !important;
        }
        img{
            width:2.2em !important;
            height:2.2em !important;
            object-fit: cover;
        }
        h4{
            font-size:20px !important;
        }
        #rank{
            margin-left:10px !important;
        }

    }

    @media (max-width: 375px) {
        #imgs{
            width:3em !important;
            height:3em !important;
            margin-bottom:0 !important;
        }
        img{
            width: 1.5em !important;
            height:1.5em !important;
            object-fit: cover;
        }
    }
</style>