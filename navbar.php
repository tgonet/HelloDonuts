<?php
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                 <a class='nav-link' href='changePassword.php'>Change Password</a></li>
                 <li class='nav-item'>
                 <a class='nav-link' href='logout.php'>Logout</a></li>";
	
	//To Do 2 (Practical 4) - 
    //Display number of item in cart
	if (isset($_SESSION["NumCartItem"])) {
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    }
}
?>

<nav class="navbar navbar-expand-md navbar-light bg-custom">
    <div class="navbar-header">
        <!-- Toggler/Collapsible Button -->
        <button class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <img src="Images/logo.svg" alt="Logo" width="70%">
        </a>
    </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light bg-custom">
    <!-- collapsible part of navbar -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <!-- left-justified menu items -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="#" class="nav-link">Donuts</a>
            </li>
        </ul>
        <!-- right-justified menu items -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="#" class="nav-link"><span class="material-icons-outlined">shopping_cart</span></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="btn btn-secondary" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="material-icons-outlined">account_circle</span></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Login</a></li>
                    <li><a href="#">Sign Up</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>