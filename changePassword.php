<?php 
session_start(); // Detect the current session

if (!isset($_SESSION["ShopperID"])) {  
    // redirect to login page if there is no shopper id in session
    header ("Location: login.php");
    exit;
}

include("header.php"); // Include the Page Layout header
?>


<script>
    function typeChangeCurrent(){
        if(document.getElementById("currentPassword").type == "password"){
            document.getElementById("currentPassword").type = "text";
            document.getElementById("checkbox").className = 'fa fa-fw field-icon toggle-password fa-eye-slash';
        }
        else{
            document.getElementById("currentPassword").type = "password";
            document.getElementById("checkbox").className = 'fa fa-fw fa-eye field-icon toggle-password';
        }
    }
    function typeChangeNew(){
        if(document.getElementById("newPassword").type == "password"){
            document.getElementById("newPassword").type = "text";
            document.getElementById("checkbox1").className = 'fa fa-fw field-icon toggle-password fa-eye-slash';
        }
        else{
            document.getElementById("newPassword").type = "password";
            document.getElementById("checkbox1").className = 'fa fa-fw fa-eye field-icon toggle-password';
        }
    }
    function typeChangeRetype(){
        if(document.getElementById("retypePassword").type == "password"){
            document.getElementById("retypePassword").type = "text";
            document.getElementById("checkbox2").className = 'fa fa-fw field-icon toggle-password fa-eye-slash';
        }
        else{
            document.getElementById("retypePassword").type = "password";
            document.getElementById("checkbox2").className = 'fa fa-fw fa-eye field-icon toggle-password';
        }
    }
	function subForm(e){
		e.preventDefault();
        if(document.changePw.retypePassword.value == document.changePw.newPassword.value){
            $.ajax({
                url:'changePasswordCall.php',
                type:'POST',
                data: $("#ChangePasswordForm").serialize(),
                success: function(response){
                    console.log(response);
                    if(response == "Current password is incorrect."){
                        alert(response);
                    }
                    else{
                        alert("Password changed.");
                        window.location.href = "index.php";
                    }
                },
                error: function (data) {
                console.log('An error occurred.');
                console.log(data);
                }
    	    });
        }
        else{
            alert("Password mismatch!");
        }
		
	}
</script>

<!-- Create a cenrally located container -->
<div style="margin: 7em 0 7em 0; padding: 0">
	<div style="width:60%; margin:auto;" class="background">
		<form id="ChangePasswordForm" method="POST" onsubmit="subForm(event)" name="changePw" action="#" style="margin: auto">
			<h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Change Password</h3>
			<div class="form-group row" style="margin-bottom:40px;">
				<label class="col-sm-3 col-form-label" for="currentPassword">Current Password:</label>
				<div class="col-sm-9">
					<input class="form-control textfield" name="currentPassword" id="currentPassword" type="password" required />
                    <span onclick="typeChangeCurrent()" id="checkbox" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				</div>
			</div>
            <div class="form-group row" style="margin-bottom:40px;">
				<label class="col-sm-3 col-form-label" for="newPassword">New Password:</label>
				<div class="col-sm-9">
					<input class="form-control textfield" name="newPassword" id="newPassword" type="password" required />
                    <span onclick="typeChangeNew()" id="checkbox1" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				</div>
			</div>
            <div class="form-group row" style="margin-bottom:40px;">
				<label class="col-sm-3 col-form-label" for="retypePassword">Retype Password:</label>
				<div class="col-sm-9">
					<input class="form-control textfield" name="retypePassword" id="retypePassword" type="password" required />
                    <span onclick="typeChangeRetype()" id="checkbox2" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				</div>
			</div>
			<button type="submit" class="center memberButton" style="padding: 10px 90px 10px 90px">Submit</button>
		</form>
	</div>
</div>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>

