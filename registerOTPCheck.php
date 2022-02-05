<?php 
    session_start(); // Detect the current session
    include("header.php"); // Include the Page Layout header
?>

<style>
    .form-control:focus{
        border-color: #cccccc;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .input-box {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 20px;
        padding-left: 0.5rem;
        overflow: hidden;
    }

    .input-box .prefix {
        font-weight: 400;
        font-size: 16px;
        color: #999;
    }

    .input-box:focus-within {
        border-color: #777;
    }
</style>

<script>
	function subForm(e){
		e.preventDefault();	
        $.ajax({
            url:'otpCheck.php',
            type:'POST',
            data: $("#OTPCheckForm").serialize(),
            success: function(response){
                if(response == "OTP is invalid. PLease try again"){
                    alert(response);
                }
                else{
                    window.location.href = "register.php";
                }
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            }
        });
	}
</script>

<!-- Create a cenrally located container -->
<div style="margin: 7em 0 7em 0; padding: 0">
	<div class="background">
		<form id="OTPCheckForm" method="POST" onsubmit="subForm(event)" action="#" style="margin: auto">
			<h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Register</h3>
            <h5>Please enter the 6 digit OTP sent to your mobile device.</h5>
            <div class="form-group row" style="margin-bottom:40px;margin-top:30px;">
                <label for="otp" class="col-sm-3 col-form-label">OTP:</label>
                <div class="col-sm-9">
                    <input type="tel" class="form-control textfield" id="otp" name="otp" minlength="6" maxlength="6" required>
                </div>
            </div>
            <button type="submit" class="center memberButton">Next</button>
		</form>
	</div>
</div>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>

