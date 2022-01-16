<?php 
session_start(); 
include("header.php"); 
?>

<div style="width:60%; margin: 0 20em 0 20em;">
<div class="row" > 
    <div class="col-12">
    <h3 style='text-align: center; padding-top: 40px; padding-bottom: 50px; font-size: 55px; color: #FA8596; font-weight: 900'>Product Categories</h3>
    </div>
</div> 

<?php 
include_once("mysql_conn.php");

$qry = "SELECT * FROM Category"; 
$result = $conn->query($qry);
while ($row = $result->fetch_array()){
    $img = "./Images/category/$row[CatImage]";
    echo "<img style='border-radius: 20%; width: 20em; height: 20em; object-fit: cover; margin-bottom: 3%;' src='$img' />";

    $catname = urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    echo "<h2><a href=$catproduct style= 'margin-left: 5.5%;'>$row[CatName]</a></h2>";
    echo "<p style='width: 15em; text-align: center; font-size: 18px; '>$row[CatDesc]</p>";
}
$conn->close(); 
echo "</div>"; 
include("footer.php"); 
?>
