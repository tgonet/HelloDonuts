<?php 
session_start();
include("header.php"); 
date_default_timezone_set('Asia/Singapore');
?>

<div style=' margin: 0 10em 0 10em;'>
<div class="row" style="padding:5px; ">
	<div class="col-12">
		<h3 style='text-align: center; padding-top: 40px; padding-bottom: 10px; font-size: 55px; color: #FA8596; font-weight: 900'><?php echo "$_GET[catName]"; ?></h3>
	</div>
	<a href="search.php" style="margin:0 auto; text-decoration: none">
	<button type="search" style="text-align: center; color: #C3BFBF; " >SEARCH...</button>
	</a>
</div>

<?php 

include_once("mysql_conn.php");

$cid=$_GET["cid"]; 
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.Offered, p.OfferedPrice, p.OfferStartDate, p.OfferEndDate
		FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
		WHERE cp.CategoryID=? ORDER BY p.ProductTitle "; 
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid); 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while ($row = $result->fetch_array()) {
	echo "<div class='row' style='padding: 5px'>"; 
	$date_now = date("Y-m-d");
	$product = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row["Price"], 2);
	$formattedOffer = number_format($row["OfferedPrice"], 2);
	echo "<div class='col-8'style='margin-top: 110px'>"; 
	echo "<h4 style ='font-size: 30px' ><a style ='color: #63200D; text-decoration: none' href=$product>$row[ProductTitle]</a></h4>";
	if (( $row["Quantity"] >0 and $row["Quantity"] <= 100) && ($row["Offered"] == 1) and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now ){
        echo "<div style='display: flex'>";
        echo "<h5>Price:<span class = 'strikethrough' style = 'font-size: 15px; color: #63200D'>
        S$ $formattedPrice</span>
		<span style = 'font-weight: bold; font-size: 25px; color: #FA8596'>S$ $formattedOffer</span></h5>";
		echo "<div class='onsale'>ON SALE</div>";
        echo "<div class='onsale2' style='margin-left: 20px'>SELLING FAST</div>";
        echo "</div>";
    }
    else if ($row["Offered"] == 1 and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now){
        echo "<h5>Price:<span class = 'strikethrough' style = 'font-size: 15px; color: #63200D'>
        S$ $formattedPrice</span>
		<span style = 'font-weight: bold; font-size: 25px; color: #FA8596'>S$ $formattedOffer</span></h5>";
		echo "<div class='onsale'>ON SALE</div>";
    }
    else if ($row["Quantity"] >0 and $row["Quantity"] <= 100){
        echo "<div class='onsale2'>SELLING FAST</div>";
		echo "<h5>Price:<span style='font-weight: bold; color: #FA8596; font-size: 18px'>
			  S$ $formattedPrice</span></h5>";
    }
	else{
		echo "<h5>Price:<span style='font-weight: bold; color: #FA8596; font-size: 18px'>
			  S$ $formattedPrice</span></h5>";
	}
	echo "</div>";

	$img = "./Images/products/$row[ProductImage]";
	echo "<div class='col-4' >";
	echo "<img style='border-radius: 10%; width: 20em; height: 20em; object-fit: cover; margin-bottom: 30px;' src='$img' alt='Images\Products\Donut_summersnow.jpg'/>";
	echo "</div>";

	echo "</div>"; 
}

$conn->close(); 
echo "</div>"; 
include("footer.php"); 
?>

<style>
    button[type="search"]{
    padding: 10px 600px 10px 10px;
    display: block; 
    border-radius: 10px  !important;
    background: transparent;
    border: #63200D solid;
    font-weight: 900;
    font-size: 30px;
    color: #63200D;
    margin:0 auto;
    margin-bottom: 80px;
}
</style>
