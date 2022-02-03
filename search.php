<?php 
session_start(); 
include("header.php"); 
?>

<form style="margin: 0 0 0 180px" name="frmSearch" method="get" action="">
    <h2 style='text-align: center; font-size: 45px; color: #63200d; font-weight: 900; padding-bottom: 50px'>Product Search</h2>
    <div class="form-group row"> 
        <label for="keywords" 
               class="col-sm-3 col-form-label search" style='font-size: 20px;' >Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button type="submit" style='font-weight: bold; color: white;'>Search</button>
        </div>
    </div> 
</form>
<a href="filter.php" style="margin:0 auto; text-decoration: none">
	<button type="moredonut" style="padding: 5px 70px 5px 70px; margin: 0 0 0 150px; font-size: 20px;" >FILTER</button>
</a>

<?php
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
	include_once("mysql_conn.php");

    $keywords=$_GET["keywords"]; 
    
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Offered,p.OfferedPrice, p.OfferStartDate, p.OfferEndDate, p.Quantity
            FROM product p 
            WHERE p.ProductTitle LIKE '%$keywords%'
            ORDER BY ProductTitle"; 
    $result = $conn->query($qry);

    while ($row = $result->fetch_array()) {
        echo "<div class='row' style='padding: 5px'>"; 
        $date_now = date("Y-m-d");
        $product = "productDetails.php?pid=$row[ProductID]";
        $formattedPrice = number_format($row["Price"], 2);
	    $formattedOffer = number_format($row["OfferedPrice"], 2);
        echo "<div class='col-8' >"; 
        echo "<h4 style='margin-top: 110px; margin-left: 150px'><a style='color: #63200D;' href=$product>$row[ProductTitle]</a></h4>";
        if (( $row["Quantity"] >0 and $row["Quantity"] <= 100) && ($row["Offered"] == 1) and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now ){
            echo "<div style='display: flex , margin-left: 150px'>";
            echo "<div class='onsale'>ON SALE</div>";
            echo "<div class='onsale2' style='margin-left: 20px'>SELLING FAST</div>";
            echo "</div>";
        }
        else if ($row["Offered"] == 1 and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now){
            echo "<div class='onsale'style='margin-left: 150px'>ON SALE</div>";
        }
        else if ($row["Quantity"] >0 and $row["Quantity"] <= 100){
            echo "<div class='onsale2'style='margin-left: 150px'>SELLING FAST</div>";
        }
        echo "</div>";
        $img = "./Images/products/$row[ProductImage]";
        echo "<div class='col-4' >";
        echo "<img style='border-radius: 10%; width: 20em; height: 20em; object-fit: cover; margin-bottom: 30px;' src='$img' />";
        echo "</div>";
        echo "</div>"; 
    }
    
    $conn->close(); 
}
echo "</div>"; 
include("footer.php"); 
?>

