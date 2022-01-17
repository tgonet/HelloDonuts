<?php 
session_start();
include("header.php"); 
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
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.Offered, p.OfferedPrice
		FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
		WHERE cp.CategoryID=? ORDER BY p.ProductTitle "; 
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid); 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while ($row = $result->fetch_array()) {
	echo "<div class='row' style='padding: 5px'>"; 

	$product = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row["Price"], 2);
	$formattedOffer = number_format($row["OfferedPrice"], 2);
	echo "<div class='col-8'style='margin-top: 110px'>"; 
	echo "<h4 style ='font-size: 30px' ><a style ='color: #63200D; text-decoration: none' href=$product>$row[ProductTitle]</a></h4>";
	if ($row["Offered"] == 1){
		echo "<h5>Price:<span class = 'strikethrough' style = 'font-size: 15px; color: #63200D'>
        S$ $formattedPrice</span>
		<span style = 'font-weight: bold; font-size: 25px; color: #FA8596'>S$ $formattedOffer</span></h5>";
		echo "<div class='onsale'>ON SALE</div>";
	}
	else{
		echo "<h5>Price:<span style='font-weight: bold; color: #FA8596; font-size: 18px'>
			  S$ $formattedPrice</span></h5>";
	}
	echo "</div>";

	$img = "./Images/products/$row[ProductImage]";
	echo "<div class='col-4' >";
	echo "<img style='border-radius: 10%; width: 20em; height: 20em; object-fit: cover; margin-bottom: 30px;' src='$img' />";
	echo "</div>";

	echo "</div>"; 
}

$conn->close(); 
echo "</div>"; 
include("footer.php"); 
?>
