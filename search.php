<?php 
session_start(); 
include("header.php"); 
?>

<form style="margin: auto" name="frmSearch" method="get" action="">
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
<a href="filter.php" style="margin:auto; text-decoration: none">
	<button type="moredonut" style="padding: 5px 70px 5px 70px; margin: 0 0 0 200px; font-size: 20px;" >FILTER</button>
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
        echo "<h4 class='here'style='margin-top: 110px; margin-left: 150px'><a style='color: #63200D;' href=$product>$row[ProductTitle]</a></h4>";
        if (( $row["Quantity"] >0 and $row["Quantity"] <= 100) && ($row["Offered"] == 1) and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now ){
            echo "<div style='display: flex'>";
            echo "<div class='onsale' style='margin-left: 150px'>ON SALE</div>";
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
        echo "<a href=$product><img class='help'style='border-radius: 10%; width: 20em; height: 20em; object-fit: cover; margin-bottom: 30px;' src='$img' /></a>";
        echo "</div>";
        echo "</div>"; 
    }
    
    $conn->close(); 
}
echo "</div>"; 
include("footer.php"); 
?>
<style>
    @media screen and (max-width: 600px) {
        button[type="moredonut"] {
			margin: 0 0 0 70px !important;
        }
        button[type="submit"] {
			margin: 30px 0 0 10px !important;
        }
        .onsale{
            margin-left:20px !important;
            width: 150px !important; 
			font-size: 15px !important;
			border-radius: 10px !important;
			height: 25px !important;
        }
        .onsale2{
            margin-left:10px !important;
            width: 150px !important; 
			font-size: 15px !important;
			border-radius: 10px !important;
			height: 50px !important;
        }
		.here{
			margin-left:20px !important;
		}
        .help{
			margin-top:100px;
			width: 100px !important; 
			height: 100px !important; 
		}
      }
</style>

