<?php 
  session_start();
  include("header.php"); 
?>
<?php 
include("offerCarousel.php"); 
?>
<div style='padding-bottom: 100px'>
<h3 style='text-align: center; padding-top: 150px; padding-bottom: 100px; font-size: 55px; color: #FA8596; font-weight: 900'>Donuts on Sale</h3>
<h3>List of Offers (TBD)</h3>
<button type="button" class="center">MORE DONUTS GOODNESS</button>
</div>
<?php 
include("footer.php"); 
?>

<style>
    .center{
        padding: 10px 90px 10px 90px;
        display: block; 
        margin: auto;
        border-radius: 10px  !important;
        background: #CAF0F8;
        border: none;
        font-weight: 900;
        font-size: 30px;
        color: #63200D;
    }
</style>