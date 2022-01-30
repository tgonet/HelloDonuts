<?php
session_start();
include("header.php");
if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}
if ($_SESSION["NumCartItem"] < 1) {
    header ("Location: index.php");
	exit;
}
?>

<!-- back to previous step -->
<div class='row' style='padding:20px 0 0 20px'>
    <div class='col-sm-12'>
        <a class='back-link' href='shoppingCart.php'>
            <i class='fas fa-chevron-left back-btn'></i> Back
        </a>
    </div>
</div>

<!-- Start a container -->
<div id='myShopCart' style='width:100%; margin:auto; text-align:center'> 

    <!-- breadcrumb -->
    <div class='mybreadcrumb'>
        <div class='row'>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content complete' href='shoppingCart.php'>
                    <span class='mybreadcrumb-circle complete'>1</span> My Tray
                </a>
            </div>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content complete' href=''>
                    <span class='mybreadcrumb-circle complete'>2</span> Delivery Mode
                </a>
            </div>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content' href=''>
                    <span class='mybreadcrumb-circle'>3</span> Review Order
                </a>
            </div>
            <div class='col-sm-3'>
                <a class='mybreadcrumb-content' href=''>
                    <span class='mybreadcrumb-circle'>4</span> Make Payment
                </a>
            </div>
        </div>
    </div> <!-- end breadcrumb -->

    <h1 class='page-title'>Delivery Mode</h1>

    <form action='reviewOrder.php' method='post' style='width: 100%; padding: 0;'>
        <div class='tray-background'> <!-- start background -->
            <div class='form-input-grp'>
                <div class='custom-control custom-radio delivery-radio'>
                    <input type='radio' class='custom-control-input' name='delivery_mode' id='Normal' value='Normal' checked>
                    <label for='Normal' class='delivery-choice custom-control-label'>
                        <div class='row'>
                            <div class='col-sm-10'>
                                <span class='delivery-name'>Normal Delivery</span></br>
                                <span class='delivery-note'>*Note: Normal delivery is free of charge when subtotal exceeds S$50.</span>
                                <?php $deliveryMode = "Normal" ?>   
                            </div>
                            <div class='col-sm-2' style='text-align: right; color: #63200d; font-weight: 600;'>$2</div>
                        </div>
                    </label></br>
                </div>
                
                <div class='custom-control custom-radio delivery-radio'>
                    <input type='radio' class='custom-control-input' name='delivery_mode' id='Express' value='Express'>
                    <label for='Express' class='delivery-choice custom-control-label'>
                        <div class='row'>
                            <div class='col-sm-10'>
                                <span class='delivery-name'>Express Delivery</span> 
                                <?php $deliveryMode = "Express" ?>   
                            </div>
                            <div class='col-sm-2' style='text-align: right; color: #63200d; font-weight: 600;'>$5</div>
                        </div>
                    </label></br>
                </div>
            </div>    
        </div> <!-- close background -->

        <!-- TODO: delivery timing -->
        

        <input type='hidden' name='action' value='mode' />
		<input type='hidden' name='shopcart_id' value='$_SESSION["Cart"]' />

        <?php $_SESSION["DeliveryMode"] = $deliveryMode ?>

        <div class='row'>
            <div class='col-md-12'>
                <button type='submit'>Continue</button>
            </div>
        </div>
    </form>

<!-- TODO: check if need to waive delivery fee -->

</div> <!-- close container -->
        
<?php
include("footer.php");
?>