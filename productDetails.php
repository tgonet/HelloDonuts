<?php 
session_start(); 
include("header.php"); 
?>
<div style='margin:auto;'>

<?php 
$pid=$_GET["pid"];

include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while ($row = $result->fetch_array()) {
    echo "<div class='row' style='margin: 3em 4em 3em 4em;'>";
    echo "<div style='margin:0 7em 0 0; '>";
    $img = "./Images/products/$row[ProductImage]";
    echo "<p><img style='border-radius: 5%; width: 40em; height: 40em; object-fit: cover; margin-bottom: 30px;'src=$img /></p>";
    echo "</div>"; 
    
    echo "<div class='col-sm-5' style='vertical-align:top; padding:5px'>"; 
    echo "<h2 style='padding-bottom: 50px; font-size: 45px; color: #63200D; font-weight: 900' >$row[ProductTitle]</h2>";
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
    echo "<span style = 'font-weight: bold; font-size: 45px; color: #FA8596'>
            S$ $formattedPrice</span>";
    echo "</div>";
}

echo "<form name='product' action='cartFunctions.php' method='post' ";
echo "<input type='hidden' name='action' value='add' />";
echo "<input type='hidden' name='product_id' value='$pid' />";
echo "Quantity: <input type='number' name='quantity' value='1'
                min='1' max='10' style='width: 70px; margin-right: 20px; ' required />";
if ($quantity <= 0){
    echo "<button style='font-weight: bold; margin-top: 30px; background-color: #B1968F ; border-radius: 25px  !important;' disabled>Add to Cart</button>";
    echo "<h2 style='font-size: 20px; margin-top: 25px'>Out of Dough!</h2>";
}
else{
    echo "<button type='submit' style='font-weight: bold; margin-top: 30px; border-radius: 25px  !important; '>Add to Cart</button>";
}
echo "</form>";
echo "</div>";
echo "<button type='rate'>RATE!</button>";
echo "</div>"; 

$conn->close();
echo "</div>"; 
include("footer.php"); 
?>
