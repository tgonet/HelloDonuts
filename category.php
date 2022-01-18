<?php 
session_start(); 
include("header.php"); 
?>

<div style="margin: 0 10em 0 10em;">
<div class="row" > 
    <div class="col-12">
    <h3 style='text-align: center; padding-top: 40px; padding-bottom: 50px; font-size: 55px; color: #FA8596; font-weight: 900'>Product Categories</h3>
    </div>
</div> 

<?php 
include_once("mysql_conn.php");

$qry = "SELECT * FROM Category"; 
$result = $conn->query($qry);
echo"<ul style='display: flex; flex-wrap: wrap; justify-content: center;'>";
while ($row = $result->fetch_array()){
    echo"<li style=' margin: 0 40px 0 40px; display: inline;'>";
    $img = "./Images/category/$row[CatImage]";
    echo "<img style='border-radius: 20%; width: 25em; height: 25em; object-fit: cover; margin-bottom: 30px;' src='$img' />";
    echo "<div style='margin-left: 20px'>";
    $catname = urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    echo "<h2><a style ='color: #63200D; margin-left: 60px; font-weight: bold' href=$catproduct>$row[CatName]</a></h2>";
    echo "<p style='width: 15em; text-align: center; font-size: 18px; margin-left: 18px;'>$row[CatDesc]</p>";
    echo "</div>";
    echo"</li>";
}
echo"</ul>";
$conn->close(); 
echo "</div>"; 
include("footer.php"); 
?>
