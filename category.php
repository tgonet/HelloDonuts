<?php 
session_start(); 
include("header.php"); 
?>

<div style="margin: 0 10% 0 10%;">
<div class="row" > 
    <div class="col-12">
    <h3 style='text-align: center; padding-top: 40px; padding-bottom: 50px; font-size: 55px; color: #FA8596; font-weight: 900'>Product Categories</h3>
<?php 
include_once("mysql_conn.php");

$qry = "SELECT * FROM Category"; 
$result = $conn->query($qry);
echo"<ul style='display: flex; flex-wrap: wrap; justify-content: center;'>";
while ($row = $result->fetch_array()){
    echo"<li style=' margin: 0 40px 0 40px; display: inline;'>";
    $catname = urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    $img = "./Images/category/$row[CatImage]";
    echo "<a href=$catproduct><img style='border-radius: 20%; width: 25em; height: 25em; object-fit: cover; margin-bottom: 30px;' src='$img' /></a>";
    echo "<div style='margin-left: 20px'>";
    echo "<h2><a style ='color: #63200D; margin-left: 60px; font-weight: bold' href=$catproduct>$row[CatName]</a></h2>";
    echo "<p style='width: 15em; text-align: center; font-size: 18px; margin-left: 18px;'>$row[CatDesc]</p>";
    echo "</div>";
    echo"</li>";
}
echo"</ul>";
$conn->close(); 
echo "</div>"; 
echo "</div>"; 
echo "</div>"; 
include("footer.php"); 
?>
