<?php 
  session_start();
  include("header.php"); 
?>

<?php 
include("offerCarousel.php"); 
?>


<h3 style='text-align: center; margin-top: 150px; margin-bottom: 70px; font-size: 55px; color: #FA8596; font-weight: 900'>Donuts on Sale</h3>

<?php
include_once("mysql_conn.php");

$qry = "SELECT * FROM Product 
        WHERE Offered = 1"; 
$result = $conn->query($qry);
echo"<ul style='display: flex; flex-wrap: wrap; justify-content: center; margin-bottom: 30px'>";

while ($row = $result->fetch_array()){
    echo"<li style=' display: inline; margin: 0 50px 50px 50px;'>";
    $img = "./Images/products/$row[ProductImage]";
    echo "<img style='border-radius: 10%; width: 18em; height: 18em; object-fit: cover; margin-bottom: 10px;' src='$img' />";
    $product = "productDetails.php?pid=$row[ProductID]";
    $formattedPrice = number_format($row["Price"], 2);
    $formattedOffer = number_format($row["OfferedPrice"], 2);
    echo "<h2><a style ='color: #63200D; margin-left:15px; font-weight: bold; font-size: 25px;text-align:center'  href=$product>$row[ProductTitle]</a></h2>";
    if ($row["Offered"] == 1){
      echo "<h5 style='margin-left:22px;'>Price:<span class = 'strikethrough' style = 'font-size: 15px; color: #63200D'>
          S$ $formattedPrice</span>
      <span style = 'font-weight: bold; font-size: 25px; color: #FA8596'>S$ $formattedOffer</span></h5>";
      echo "<div class='onsale' style='margin-left:55px;'>ON SALE</div>";
    }
    echo"</li>";
}
echo"</ul>";
$conn->close(); 
echo "</div>";

?>
<a href="category.php" style="margin:0 auto; text-decoration: none">
  <button type="moredonut" style="margin-bottom: 50px;">MORE DONUTS GOODNESS</button>
</a>
<?php 
include("footer.php"); 
?>
