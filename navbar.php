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

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="Image/doughnut_pink.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
      <h1>hello donuts</h1>
    </a>
  </div>
</nav>