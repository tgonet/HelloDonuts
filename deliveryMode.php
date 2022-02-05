<?php
include_once("deliveryFunction.php");
if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}
if ($_SESSION["NumCartItem"] < 1) {
    header ("Location: index.php");
	exit;
}
include("header.php");
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

    <h3 class='outsideBackground'>Delivery Mode</h3>

    <form action='deliveryFunction.php' method='post' style='width: 100%; padding: 0;'>
        <div class='tray-background'> <!-- start background -->
            <div class='form-input-grp'>
                <div class='custom-control custom-radio delivery-radio'>
                    <input type='radio' class='custom-control-input' name='delivery_mode' id='Normal' value='Normal' checked onclick='onClickShow()'>
                    <label for='Normal' class='delivery-choice custom-control-label'>
                        <div class='row'>
                            <div class='col-sm-10'>
                                <span class='delivery-name'>Normal Delivery</span></br>
                                <span class='delivery-desc'>Your donuts will be delivered within 1 working day.</span> </br>
                                <span class='delivery-note'>*Note: Normal delivery is free of charge when subtotal exceeds S$50.</span>
                            </div>
                            <div class='col-sm-2' style='text-align: right; color: #63200d; font-weight: 600;'>$2</div>
                        </div>
                    </label></br>
                </div>
                
                <div class='custom-control custom-radio delivery-radio'>
                    <input type='radio' class='custom-control-input' name='delivery_mode' id='Express' value='Express' onclick='onClickHide()'>
                    <label for='Express' class='delivery-choice custom-control-label'>
                        <div class='row'>
                            <div class='col-sm-10'>
                                <span class='delivery-name'>Express Delivery</span></br>
                                <span class='delivery-desc'>Your donuts will be delivered within 2 hours (for orders placed before 5:30 pm).</span> 
                            </div>
                            <div class='col-sm-2' style='text-align: right; color: #63200d; font-weight: 600;'>$5</div>
                        </div>
                    </label></br>
                </div>
            </div>    
        </div> <!-- close background -->

        <!-- delivery timing -->
        <div id='normal-time'>
            <h3 class='outsideBackground'>Delivery Timing</h3>

            <div class='tray-background'> <!-- start background -->
            <div class='form-input-grp'>
                    <div class='custom-control custom-radio delivery-radio'>
                        <input type='radio' class='custom-control-input' name='delivery_time' id='9to12' value='9to12' checked>
                        <label for='9to12' class='delivery-choice custom-control-label'>
                            <div class='row'>
                                <div class='col-sm-10'>
                                    <span class='delivery-name'>9 am - 12 pm</span></br>
                                </div>
                            </div>
                        </label></br>
                    </div>
                    
                    <div class='custom-control custom-radio delivery-radio'>
                        <input type='radio' class='custom-control-input' name='delivery_time' id='12to3' value='12to3'>
                        <label for='12to3' class='delivery-choice custom-control-label'>
                            <div class='row'>
                                <div class='col-sm-10'>
                                    <span class='delivery-name'>12 pm - 3 pm</span></br>
                                </div>
                            </div>
                        </label></br>
                    </div>
                    
                    <div class='custom-control custom-radio delivery-radio'>
                        <input type='radio' class='custom-control-input' name='delivery_time' id='3to6' value='3to6'>
                        <label for='3to6' class='delivery-choice custom-control-label'>
                            <div class='row'>
                                <div class='col-sm-10'>
                                    <span class='delivery-name'>3 pm - 6 pm</span> 
                                </div>
                            </div>
                        </label></br>
                    </div>
                </div>
            </div> <!-- close background -->
        </div>

        <div class='row'>
            <div class='col-md-12'>
                <button type='submit'>Continue</button>
            </div>
        </div>
    </form>
</div> <!-- close container -->

<script>
    function onClickHide(){
        $("#normal-time").hide();
    }

    function onClickShow(){
        $("#normal-time").show();
    }
</script>

<?php
include("footer.php");
?>