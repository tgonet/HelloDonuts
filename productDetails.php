<?php 
session_start(); 
include("header.php"); 
date_default_timezone_set('Asia/Singapore');
?>
<div style='margin:auto;'>

<?php 
$pid=$_GET["pid"];
$_SESSION["pid"] = $pid;

include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while ($row = $result->fetch_array()) {
    echo "<div style='display: flex; flex-wrap: wrap; justify-content: center; margin-bottom: 30px'>";
    echo "<div class='row' style='margin: 3em 4em 3em 4em; '>";
    echo "<div style='margin:0 7em 0 0; display: inline;'>";
    $img = "./Images/products/$row[ProductImage]";
    $_SESSION["rateImage"] = $img;
    $_SESSION["rateName"] = $row["ProductTitle"]; 
    echo "<p><img style='border-radius: 5%; width: 40em; height: 40em; object-fit: cover; margin-bottom: 30px;'src=$img /></p>";
    echo "</div>"; 
    $date_now = date("Y-m-d");
    echo "<div class='col-sm-5' style='vertical-align:top; padding:5px; display: inline;'>"; 
    echo "<h2 style='padding-bottom: 15px; font-size: 45px; color: #63200D; font-weight: 900' >$row[ProductTitle]</h2>";
    if (( $row["Quantity"] >0 and $row["Quantity"] <= 100) && ($row["Offered"] == 1) and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now ){
        echo "<div style='display: flex'>";
        echo "<div class='onsale'>ON SALE</div>";
        echo "<div class='onsale2' style='margin-left: 20px'>SELLING FAST</div>";
        echo "</div>";
    }
    else if ($row["Offered"] == 1 and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now){
        echo "<div class='onsale'>ON SALE</div>";
    }
    else if ($row["Quantity"] >0 and $row["Quantity"] <= 100){
        echo "<div class='onsale2'>SELLING FAST</div>";
    }
    
    echo "<h4>$row[ProductDesc]</h4>";

    $qry =  "SELECT s.SpecName, ps.SpecVal from productspec ps
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=?
            ORDER BY ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid); 
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();

    $qry2 = "SELECT AVG(RankInput) AS rating FROM ranking where productid=?";
    $stmt = $conn->prepare($qry2);
    $stmt->bind_param("i", $pid); 
    $stmt->execute();
    $resultRow = $stmt->get_result();
    $stmt->close();
    $row3 = $resultRow->fetch_array();  
    while ($row2 = $result2->fetch_array()) {
        echo "<div style='margin-top: 10px;'>";
        echo "<h6>$row2[SpecName]".": "."<span style='font-weight:bold;'>$row2[SpecVal]</span>"."<br /></h6>";
        echo "</div>";
    }
    echo "<div style='margin-top: 10px; font-size: 23px; color: #63200D;'>";
    $quantity = $row["Quantity"];
    echo "Qty Left: <span style = 'font-weight: bold;'>
		$quantity</span>";
    echo "</div>";
    echo "<div style='margin-top: 20px;'>";
    $formattedPrice = number_format($row["Price"], 2);
    $formattedOffer = number_format($row["OfferedPrice"], 2);
    $rating = round($row3["rating"]);
    echo "<div style='display:block;'>";
    for ($x = 1; $x <= $rating; $x++) {
        echo "<img src='Images/rating_show.png' style='margin-right:10px;'>";
      }
    for ($x = 1; $x <= 5-$rating; $x++) {
        echo "<img src='Images/rating_unshown.png' style='margin-right:10px;'>";
    }
    echo "</div>";
    if ($row["Offered"] == 1 and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now){
        echo "<span class = 'strikethrough' style = 'font-size: 20px; color: #63200D'>
        S$ $formattedPrice</span>";
        echo "<span style = 'font-weight: bold; font-size: 45px; color: #FA8596'>
        S$ $formattedOffer</span>";
    }
    else{
        echo "<span style = 'font-weight: bold; font-size: 45px; color: #FA8596'>
                S$ $formattedPrice</span>";
    }
    echo "</div>";
}




echo "<form name='product' action='cartFunctions.php' method='post' style='margin: 0 0 0 0 !important'>";
echo "<input type='hidden' name='action' value='add' />";
echo "<input type='hidden' name='product_id' value='$pid' />";
echo "Quantity: <input type='number' name='quantity' value='1'
                min='1' max='10' style=' margin-right: 20px; ' required />";
if ($quantity <= 0){
    echo "<button style='font-weight: bold; margin-top: 30px; background-color: #B1968F ; border-radius: 25px  !important;' disabled>Add to Cart</button>";
    echo "<h2 style='font-size: 20px; margin-top: 25px'>Out of Dough!</h2>";
}
else{
    echo "<button type='submit' style='font-weight: bold; margin-top: 30px; border-radius: 25px  !important; '>Add to Cart</button>";
}
echo "</form>";
echo "</div>";
echo "<button type='rate' style='display: inline-block;'>RATE!</button>";
echo "</div>"; 
echo "</div>"; 
include_once("ratingSection.php");
echo "<div class='suggest'>";
echo "<div class='row' style='margin: 3em 4em 3em 4em;'>";
echo "<h3 style='margin-top:50px; color: #63200D; width: 100%;'>You may like this:</h3>";
$qry = "SELECT * from product where NOT ProductID=? ORDER BY RAND() LIMIT 3"; 
$result = $conn->query($qry);
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
echo"<ul style='display: flex; flex-wrap: wrap; justify-content: center; margin-bottom: 30px'>";
while ($row = $result->fetch_array()){
    echo"<li style=' display: inline; margin: 0 50px 50px 50px;'>";
    $img = "./Images/products/$row[ProductImage]";
    echo "<div style='height:85%; width:100%;'>";
    echo "<img style='border-radius: 10%; width: 18em; height: 18em; object-fit: cover;' src='$img' />";
    echo "</div>"; 
    $product = "productDetails.php?pid=$row[ProductID]";
    echo "<h2><a style ='color: #63200D; margin-left:15px; font-weight: bold; font-size: 25px;text-align:center'  href=$product>$row[ProductTitle]</a></h2>";
    echo"</li>";
}
echo"</ul>";
echo "</div>"; 
echo "</div>"; 

$conn->close();
echo "</div>"; 
include("footer.php"); 
?>

<style>
    button[type="rate"]{
    display: inline-block; 
    margin-left: 100px;
    border-radius: 30px  !important;
    background: #CAF0F8;
    border: none;
    font-weight: 900;
    font-size: 45px;
    color: #63200D;
    clear: both;
    vertical-align: bottom;
    width: 300px;
    height: 100px;
}
    .suggest{
   margin-top: 150px;
   background-color: #F2F2F2;
   width: 100%;
   height: 500px !important;
}
</style>