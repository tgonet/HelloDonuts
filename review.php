<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<!-- CSS to invert columns -->
<!-- Create a centrally located container -->
<div style="width:80%; margin:auto; text-align:center">
    <h1>Your Order</h1>
    <div class="row" style="margin-top:10%; align-items:center">
        <div class="col-sm-6">
            <h2>SLOGAN</h2>
        </div>
        <div class="col-sm-6">
            <p style="font-family:'Sacramento'; font-size: 40px;">Do not go a day without donuts, Say hello to donuts</p>
        </div>
    </div>
</div>
<?php
//Include the Page Layout footer
include("footer.php");
?>