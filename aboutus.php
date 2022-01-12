<?php
//Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<!-- Create a centrally located container -->
<div style="width:80%; margin:auto; text-align:center">
    <h1>ABOUT US </h1>
    <div class="row" style="margin-top:10%; align-items:center">
        <div class="col-sm-6">
            <h2>SLOGAN</h2>
        </div>
        <div class="col-sm-6">
            <p style="font-family:'Sacramento'; font-size: 40px;">Do not go a day without donuts, Say hello to donuts</p>
    </div>
    <div class="row" style="margin-top:5%; align-items:center">
        <div class="col-sm-6">
                <p style="font-family:'Sacramento'; font-size: 40px;align-items:center">To serve the best donuts, freshly made for you</p>
        </div>
        <div class="col-sm-6">
            <h2>OUR MISSION</h2>
        </div>
    </div>
</div>
<?php
//Include the Page Layout footer
include("footer.php");
?>

<style>
    h1,h2 {
        color: pink;
        font-family:'Quicksand';
    }

</style>