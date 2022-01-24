<?php 
    session_start();
    include("header.php");
    $img = $_SESSION["rateImage"];
    $name = $_SESSION["rateName"];
?>

<script>
    function back(){
        history.go(-1);
    }
    </script>

<!-- back to previous step -->
<div class='row' style='padding:20px 0 0 20px'>
    <div class='col-sm-12'>
        <a class='back-link' onclick="back()">
            <i class='fas fa-chevron-left back-btn'></i> Back
        </a>
    </div>
</div>

<?php
    echo "<div class='background'>";
    echo "<td style='width: 8em;'><img src=$img class='donut-img'></td>";
    echo "<span style:'margin-left:10px;'>$name</span>";
    echo "</div>";
?>