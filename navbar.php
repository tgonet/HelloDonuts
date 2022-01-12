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
        <a class="navbar-brand d-lg-none navbar-logo" href="#">
            <img src="Images/hello_donut_logo.svg" alt="Logo" class="logo-img">
        </a>
        <!-- Toggler/Collapsible Button -->
        <button class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light bg-custom">
    <!-- collapsible part of navbar -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <!-- left-justified menu items -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="#" class="nav-link">DONUTS</a>
            </li>
        </ul>
        <ul class="navbar-nav mx-auto">
            <a class="d-none d-lg-block navbar-logo" href="#">
                <img src="Images/hello_donut_logo.svg" alt="Logo" class="logo-img">
            </a>
        </ul>
        <!-- right-justified menu items -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-shopping-cart nav-icon"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle nav-icon"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a class="dropdown-item nav-item-dd" href="#">Login</a>
                    </li>
                    <li>
                        <a class="dropdown-item nav-item-dd" href="#">Sign Up</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
