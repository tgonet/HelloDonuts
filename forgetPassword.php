<?php 
session_start(); // Detect the current session

include("header.php"); // Include the Page Layout header
?>


<script>
	function subForm(e){
		e.preventDefault();
		$.ajax({
			url:'forgetPasswordBackend.php',
			type:'POST',
			data: $("#testForm").serialize(),
			success: function(response){
				console.log(response);
				if(response == "Please check your security question and answer"){
					alert(response);
				}
                else if(response == "Invalid User"){
                    alert(response);
                }
				else{
					alert("Password changed. \nYour new password is password.");
					window.location.href = "login.php";
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
	<div style="width:60%; margin:auto;" class="background">
		<form id="testForm" method="POST" onsubmit="subForm(event)" action="#" style="margin: auto">
			<h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Forget Password</h3>
			<div class="form-group row" style="margin-bottom:40px;">
				<label class="col-sm-3 col-form-label" for="eMail">Email:</label>
				<div class="col-sm-9">
					<input class="form-control textfield" name="eMail" id="eMail" type="email" required />
				</div>
			</div>
			<div class="form-group row" style="margin-bottom:40px;">
				<label for="SecQns" class="col-sm-3 col-form-label">Security Question: </label>
				<div class="col-sm-9">
					<select id="SecQns" name="SecQns" class="textfield form-control minimal" required>
						<option value="" disabled selected>Select the question you set during registration</option>
						<option value="Which polytechnic?">Which polytechnic?</option>
                        <option value="Wife's name?">Wife's name?</option>
                        <option value="How many brothers and sisters?">How many brothers and sisters?</option>
					</select>
				</div>
			</div>
			<div class="form-group row" style="margin-bottom:40px;">
				<label for="SecAns" class="col-sm-3 col-form-label">Security Answer: </label>
				<div class="col-sm-9">
					<input class="form-control textfield" type="test" name="SecAns" id="SecAns" placeholder="Please enter your answer" required/>
				</div>
			</div>
			<button type="submit" class="center" style="padding: 10px 90px 10px 90px">Submit</button>
		</form>
	</div>
</div>

</div> <!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>
