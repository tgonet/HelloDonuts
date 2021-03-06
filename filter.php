<?php 
session_start();
include("header.php"); 
date_default_timezone_set('Asia/Singapore');
?>

<form style="margin: auto" name="frmSearch" method="get" action="">
    <div class="background" style='margin-top: 30px !important; width: 95% !important; height: 300px !important;'>
        <div style='display: flex'>
            <span style="font-size: 25px; margin: 0 18px 18px 18px">SWEETNESS:</span>
            <div style="width: 80%; margin: 10px 18px 18px ">
                <input type="range" min="1" max="5" value="3" class="slider" name= "myRange" id="myRange">
                <p>Value: <span id="demo"></span></p>
            </div>
        </div>
        <div style='display:flex'>
            <span style="font-size: 25px; width: 200px; margin: 30px 18px 18px 18px; justify-content: center">Price Range:</span>
            <div class="price-input" style="width: 100%; display: flex; margin: 30px 0 35px;">
                <div class="field">
                    <span style="margin-left: 10px">Min</span>
                    <input type="number" class="input-min" name="input-min" id="input-min"step=".01">
                </div>
                <div class="separator">-</div>
                <div class="field">
                    <span style="margin-left: 30px">Max</span>
                    <input type="number" class="input-max" name= "input-max" id="input-max" step=".01">
                </div>
            </div>
        </div>
    <button type="submit" style='font-weight: bold; color: white; float: right; margin-right: 10px'>Filter</button>
    </div>
</form>

<?php

if (($_GET["input-max"] && $_GET["input-min"]  != "") || $_GET["myRange"]  != ""  ) {
    include_once("mysql_conn.php");
    if ($_GET["input-max"] && $_GET["input-min"]  != "" && $_GET["myRange"]  != "" ) {
        $priceMax = $_GET["input-max"];
        $priceMin = $_GET["input-min"];
        $sweet = $_GET["myRange"];
        $date_now = date("Y-m-d");
        $qry =  "SELECT s.SpecName, ps.SpecVal, p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Offered, p.OfferedPrice, p.OfferStartDate, p.OfferEndDate from productspec ps
                        INNER JOIN specification s ON ps.SpecID=s.SpecID
                        INNER JOIN product p ON ps.ProductID=p.ProductID
                        WHERE ps.SpecID = 2 AND (ps.SpecVal BETWEEN 0 AND $sweet) AND((p.Price BETWEEN $priceMin AND $priceMax) OR (p.OfferedPrice BETWEEN $priceMin AND $priceMax AND (p.OfferStartDate < now() AND p.OfferEndDate>now())))
                        ORDER BY ProductTitle";

        $result = $conn->query($qry);
        while ($row = $result->fetch_array()) {
            echo "<div class='row' style='padding: 5px'>"; 
            $date_now = date("Y-m-d");
            $formattedPrice = number_format($row["Price"], 2);
	        $formattedOffer = number_format($row["OfferedPrice"], 2);
            $product = "productDetails.php?pid=$row[ProductID]";
            echo "<div class='col-8' >"; 
            echo "<h4 style='margin-top: 110px; margin-left: 150px'><a style='color: #63200D;' href=$product>$row[ProductTitle]</a></h4>";
            echo "<h4 style='margin-left: 150px; font-size: 18px'>Sweetness: <span>$row[SpecVal]</span><h4>";
            if ($row["Offered"] == 1 and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now){
                echo"<div style='margin-left: 150px;'>";
                echo "<h5>Price:<span class = 'strikethrough' style = 'font-size: 15px; color: #63200D'>
                S$ $formattedPrice</span>
                <span style = 'font-weight: bold; font-size: 25px; color: #FA8596'>S$ $formattedOffer</span></h5>";
                echo "<div class='onsale'>ON SALE</div>";
                echo"</div>";
            }
            else{
                echo"<div style='margin-left: 150px;'>";
                echo "<h5>Price:<span style='font-weight: bold; color: #FA8596; font-size: 18px'>
                      S$ $formattedPrice</span></h5>";
                echo"</div>";
            }
            
            echo "</div>";
            $img = "./Images/products/$row[ProductImage]";
            echo "<div class='col-4' >";
            echo "<a href=$product><img style='border-radius: 10%; width: 20em; height: 20em; object-fit: cover; margin-bottom: 30px;' src='$img' /></a>";
            echo "</div>";
            echo "</div>"; 
        }
        
        $conn->close(); 
        
    }
    else if ($_GET["myRange"]  != ""){
        
        $sweet = $_GET["myRange"];
        $qry =  "SELECT s.SpecName, ps.SpecVal, p.ProductID, p.ProductTitle, p.ProductImage, p.Offered,p.Price,p.OfferedPrice, p.OfferStartDate, p.OfferEndDate from productspec ps
                INNER JOIN specification s ON ps.SpecID=s.SpecID
                INNER JOIN product p ON ps.ProductID=p.ProductID
                WHERE ps.SpecID = 2 AND ps.SpecVal BETWEEN 0 AND $sweet
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
            echo "<h4 style='margin-left: 150px; font-size: 18px'>Sweetness: <span>$row[SpecVal]</span><h4>";
            if ($row["Offered"] == 1 and $row["OfferStartDate"] <= $date_now and $row["OfferEndDate"] >= $date_now){
                echo"<div style='margin-left: 150px;'>";
                echo "<h5>Price:<span class = 'strikethrough' style = 'font-size: 15px; color: #63200D'>
                S$ $formattedPrice</span>
                <span style = 'font-weight: bold; font-size: 25px; color: #FA8596'>S$ $formattedOffer</span></h5>";
                echo "<div class='onsale'>ON SALE</div>";
                echo"</div>";
            }
            else{
                echo"<div style='margin-left: 150px;'>";
                echo "<h5>Price:<span style='font-weight: bold; color: #FA8596; font-size: 18px'>
                      S$ $formattedPrice</span></h5>";
                echo"</div>";
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
    else {
        $priceMax = $_GET["input-max"];
        $priceMin = $_GET["input-min"];
        $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Offered
            FROM product p 
            WHERE p.Price BETWEEN $priceMin AND $priceMax
            ORDER BY ProductTitle"; 
        $result = $conn->query($qry);
        while ($row = $result->fetch_array()) {
            echo "<div class='row' style='padding: 5px'>"; 
    
            $product = "productDetails.php?pid=$row[ProductID]";
            echo "<div class='col-8' >"; 
            echo "<h4 style='margin-top: 110px; margin-left: 150px'><a style='color: #63200D;' href=$product>$row[ProductTitle]</a></h4>";
            if ($row["Offered"] == 1){
                echo "<div class='onsale' style='margin-left: 150px'>ON SALE</div>";
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
}
echo "</div>"; 
include("footer.php"); 
?>

<script>
var slider = document.getElementById("myRange");
var output = document.getElementById("demo");
output.innerHTML = slider.value;

slider.oninput = function() {
  output.innerHTML = this.value;
}
</script>

<style>
    .slider {
    -webkit-appearance: none;
    width: 100%;
    height: 1px;
    border-radius: 5px;
    background: white;
    outline: none;
    opacity: 0.7;
    position: relative;
    -webkit-transition: .2s;
    transition: opacity .2s;
  }
  
  .slider:hover {
    opacity: 1;
  }
  
  .slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    cursor: pointer;
  }
  
  .slider::-moz-range-thumb {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    cursor: pointer;
  }
.price-input .field{
    height: 45px;
    width: 50%;
    display: flex;
    align-items: center;
}

.field input{
    height: 100%;
    outline: none;
    font-size: 19px;
    text-align: center;
    margin-left: 5px;
    border-radius: 5px;
    border: 1px solid white;
}

.price-input .separator{
    padding-left: 40px;
    width: 100px;
    display: flex;
    font-size: 19px;
    align-items: center;
}
</style>