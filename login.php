<?php
    //Detext the current session
    session_start();

    //Include the page layout header
    include("header.php");
?>

<div style="margin: 7em 0 7em 0; padding: 0">
    <div class="background">
        <form action="checkLogin.php" method="post">
            <h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Login</h3>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="email" class="col-sm-3 col-form-label">Email:</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control textfield" id="email" name="email" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label">Password: </label>
                <div class="col-sm-9">
                    <input class="form-control textfield" type="password" name="password" id="password" required/>
                </div>
            </div>
            <p align="right" style="margin-top: -15px"><a  href="forgetPassword.php">Forget Password</a></p>
            <button type="login" class="center" style="padding: 10px 90px 10px 90px">Time to get that dough!</button>
            <p align="center" style="padding-top: 25px; font-size: 23px;">New User? <a href="register.php" style="font-weight: 600;color:black">Register Here</a></p>
        </form>
    </div>
</div>


<?php
    //Include the page layout header
    include("footer.php");
?>