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
        if(document.otp.phone.value != ""){
            e.preventDefault();
            var str = document.otp.phone.value;
            if(str.length != 8){
                alert("Please enter a 8-digit phone number.");
                return;
            }
            else if(str.substr(0,1) != "6" &&
                    str.substr(0,1) != "8" &&
                    str.substr(0,1) != "9"){
                alert("Phone number in Singapore should start with 6,8 or 9.");
                return;
            }
            else{
                $.ajax({
                    url:'otpSubmit.php',
                    type:'POST',
                    data: $("#OTPForm").serialize(),
                    success: function(response){
                        //alert(response);
                        window.location.href = "registerOTPCheck.php";
                    },
                    error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                    }
                });
            }
            
        }
        
	}
</script>

<!-- Create a cenrally located container -->
<div style="margin: 7em 0 7em 0; padding: 0">
	<div style="width:60%; margin:auto;" class="background">
		<form id="OTPForm" method="POST" onsubmit="subForm(event)" name="otp" action="#" style="margin: auto">
			<h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Register</h3>
            <h5>Please enter your mobile number.</h5>
            <h5 style="color: #AFAFAF;">You will receive a 6 digit pin for mobile verification</h5>
            <div class="form-group row" style="margin-bottom:40px;margin-top:30px;">
                <label for="phone" class="col-sm-3 col-form-label">Phone:</label>
                <div class="col-sm-9">
                    <div class="input-box">
                        <span class="prefix">+65</span>
                        <input type="tel" class="form-control textfield" id="phone" name="phone" placeholder="Please enter your mobile number" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="center" style="padding: 10px 90px 10px 90px">Next</button>
		</form>
	</div>
</div>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>

