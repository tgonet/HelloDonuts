<?php
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$itemsintray = "";
$content1 = "Guest";
$content2 = "<ul class='dropdown-menu dropdown-menu-right'>
                <li>
                    <span class='dropdown-item nav-user'>Hi, Guest</span>
                </li>
                <li class='dropdown-divider'></li>
                <li>
                    <a class='dropdown-item nav-item-dd' href='login.php'>Login</a>
                </li>
                <li>
                    <a class='dropdown-item nav-item-dd' href='register.php'>Sign Up</a>
                </li>
            </ul>";

if(isset($_SESSION["ShopperName"])) { 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
    $content1 = "$_SESSION[ShopperName]";
    $content2 = "<ul class='dropdown-menu dropdown-menu-right'>
                    <li>
                        <span class='dropdown-item nav-user'>Hi, $_SESSION[ShopperName]</span>
                    </li>
                    <li class='dropdown-divider'></li>
                    <li>
                        <a class='dropdown-item nav-item-dd' href='editProfile.php'>Profile</a>
                    </li>
                    <li>
                        <a class='dropdown-item nav-item-dd' href='orderHistory.php'>Order History</a>
                    </li>
                    <li>
                        <a class='dropdown-item nav-item-logout' href='logout.php'>Log Out</a>
                    </li>
                </ul>";
	
    //Display number of item in cart
	if (isset($_SESSION["NumCartItem"])) {
        $itemsintray = "<span class='badge'>$_SESSION[NumCartItem]</span>";
    }
}
?>

<nav class="navbar navbar-expand-md navbar-light">
    <div class="navbar-header">
        <a class="navbar-brand d-lg-none navbar-logo" href="index.php">
            <img src="Images/hello_donut_logo.svg" alt="Logo" class="logo-img">
        </a>
        <!-- Toggler/Collapsible Button -->
        <button class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light">
    <!-- collapsible part of navbar -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <!-- left-justified menu items -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="category.php" class="nav-link">Donuts</a>
            </li>
            <div class="vl d-none d-lg-block"></div>
            <li class="nav-item ">
                <a href="search.php" class="nav-link">
                    Search
                </a>
            </li>
        </ul>
        <ul class="navbar-nav mx-auto">
            <a class="d-none d-lg-block navbar-logo" href="index.php">
                <img src="Images/hello_donut_logo.svg" alt="Logo" class="logo-img">
            </a>
        </ul>
        <!-- right-justified menu items -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="shoppingCart.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <?php echo $itemsintray; ?>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </a>
                <?php echo $content2; ?>
            </li>
        </ul>
    </div>
</nav>
