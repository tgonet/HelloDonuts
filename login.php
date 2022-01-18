<?php
    //Detext the current session
    session_start();

    //Include the page layout header
    include("header.php");
?>

<script>
    function typeChange(){
        if(document.getElementById("password").type == "password"){
            document.getElementById("password").type = "text";
            document.getElementById("checkbox").className = 'fa fa-fw field-icon toggle-password fa-eye-slash';
        }
        else{
            document.getElementById("password").type = "password";
            document.getElementById("checkbox").className = 'fa fa-fw fa-eye field-icon toggle-password';
        }
    }

    function subForm(e){
		e.preventDefault();
		$.ajax({
			url:'checkLogin.php',
			type:'POST',
			data: $("#LoginForm").serialize(),
			success: function(response){
				console.log(response);
				if(response == "Your email or password is incorrect."){
                    document.getElementById("loginMessage").innerText = response;
				}
				else{
					window.location.href = "index.php";
				}
			},
			error: function (data) {
                console.log('An error occurred.');
                console.log(data);
        }
    	});
	}
</script>

<div style="margin: 7em 0 7em 0; padding: 0">
    <div class="background">
        <form action="#" method="post" id="LoginForm" onsubmit="subForm(event)">
            <h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Login</h3>
            <p style="color:red" align="center" id="loginMessage"></p>
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
                    <span onclick="typeChange()" id="checkbox" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
            </div>
            <p align="right" style="margin-top: -15px"><a style="color:black" href="forgetPassword.php">Forget Password</a></p>
            <button type="login" class="center">Time to get that dough!</button>
            <p align="center" style="padding-top: 25px; font-size: 23px;">New User? <a href="register.php" style="font-weight: 600;color:black">Register Here</a></p>
        </form>
    </div>
</div>


<?php
    //Include the page layout header
    include("footer.php");
?>