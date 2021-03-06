<?php 
    session_start();
    if (!isset($_SESSION["ShopperID"])) {  
        // redirect to login page if there is no shopper id in session
        header ("Location: login.php");
        exit;
    }
    include("header.php");
    $img = $_SESSION["rateImage"];
    $name = $_SESSION["rateName"];
?>

<script>
    function back(){
        history.go(-1);
    }

    function validateForm(e)
    {
        e.preventDefault();
        $.ajax({
                url:'ratingCode.php',
                type:'POST',
                data: $("#ratingForm").serialize(),
                success: function(response){
                    console.log(response);
                    alert("Rating submitted. \nClick'OK' to continue shopping with us.");
                    history.go(-1);
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
            }
            });
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
        echo "<div style='margin-left:20px;margin-right:20px;overflow:hidden'>";
            echo "<h3 style='padding-bottom: 15px; font-size: 35px; color: #63200D; font-weight: 900; margin-bottom: 0px;' >$name</h3>";
            echo "<form style='margin: 0px; width:100%; padding:0px;' action='#' method='post' id='ratingForm' onsubmit='validateForm(event)'>";
            echo "<div class='rating'>
                <input id='rating1' type='radio' name='rating' value='1'>
                <label for='rating1'>1</label>
                <input id='rating2' type='radio' name='rating' value='2'>
                <label for='rating2'>2</label>
                <input id='rating3' type='radio' name='rating' value='3'>
                <label for='rating3'>3</label>
                <input id='rating4'type='radio' name='rating' value='4'>
                <label for='rating4'>4</label>
                <input id='rating5' type='radio' name='rating' value='5'>
                <label for='rating5'>5</label>
            </div>";
            echo "<textarea class='form-control textfield' style='margin-bottom:10px;' id='comment' name='comment' rows='4' cols='50' placeholder='Share your review on our products' required></textarea>";
            echo "<button type='submit' align='center' style='font-size:14px;float:right;margin-bottom:10px;'>Submit</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";

?>

<?php
    //Include the page layout header
    include("footer.php");
?>

<style> 
  .rating{
    display : flex;
  }

  .rating input{
    position : absolute;
    left     : -100vw;
  }

  .rating label{
    width      : 48px;
    height     : 48px;
    padding    : 48px 0 0;
    overflow   : hidden;
    background : url('Images/donuts.svg') no-repeat top left;
  }

  .rating:not(:hover) input:indeterminate + label,
  .rating:not(:hover) input:checked ~ input + label,
  .rating input:hover ~ input + label{
    background-position : -48px 0;
  }

  .rating:not(:hover) input:focus-visible + label{
    background-position : -96px 0;
  }
</style>   
