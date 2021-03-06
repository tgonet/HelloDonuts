<?php
    //Detext the current session
    session_start();

    //Include the page layout header
    include("header.php");
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
    window.onload = function() {
    var recaptcha = document.forms["register"]["g-recaptcha-response"];
    recaptcha.required = true;
    recaptcha.oninvalid = function(e) {
    alert("Please verify with CAPTCHA before continuing.");
      }
   }
   
   function getAddress(value){
        $.ajax({
            url:'https://developers.onemap.sg/commonapi/search?searchVal=' + value + '&returnGeom=Y&getAddrDetails=Y',
            type:'GET',
            success: function(response){
                document.register.address.value = response.results["0"].ADDRESS;
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            }
        });
   }
     function getDate(){
        // For date input (Max Today)
        var today = new Date();
        var dd = today.getDate();
         mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        } 
    
        today = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        document.getElementById("bday").setAttribute("max", today);
    }

    function validateForm(e)
    {
        e.preventDefault();
        if(document.register.password.value != document.register.password2.value){
            alert("Passwords do not match!");
            //return;
        }
        else{
            $.ajax({
                url:'addMember.php',
                type:'POST',
                data: $("#RegisterForm").serialize(),
                success: function(response){
                    console.log(response);
                    if(response == "This email is taken. Please use another email or login instead."){
                        alert(response);
                    }
                    else if(response == "Error in inserting record"){
                        alert(response);
                    }
                    else{
                        alert(response);
                        window.location.href = "index.php";
                    }
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                }
        });
        }
    }
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
    function typeChangeReset(){
        if(document.getElementById("password2").type == "password"){
            document.getElementById("password2").type = "text";
            document.getElementById("checkbox2").className = 'fa fa-fw field-icon toggle-password fa-eye-slash';
        }
        else{
            document.getElementById("password2").type = "password";
            document.getElementById("checkbox2").className = 'fa fa-fw fa-eye field-icon toggle-password';
        }
    }
</script>

<div style="margin: 7em 0 7em 0; padding: 0">
    <div class="background">
        <form id="RegisterForm" style="margin: auto" name="register" action="#" method="post" onsubmit="validateForm(event)">
            <h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Register</h3>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="name" class="col-sm-3 col-form-label">Full Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control textfield" id="name" name="name" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="bday" class="col-sm-3 col-form-label">Date of Birth:</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control textfield" id="bday" name="bday" min="1900-01-01" onclick="getDate()" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="postalCode" class="col-sm-3 col-form-label">Postal Code:</label>
                <div class="col-sm-9">
                    <input onchange="getAddress(value)" type="text" class="form-control textfield" id="postalCode" name="postalCode" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="address" class="col-sm-3 col-form-label">Address:</label>
                <div class="col-sm-9">
                    <textarea class="form-control textfield" id="address" name="address" rows="4" cols="50" placeholder="Please enter your address"  required></textarea>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="unitNo" class="col-sm-3 col-form-label">Unit No:</label>
                <div class="col-sm-9">
                    <input onchange="getAddress(value)" type="text" class="form-control textfield" id="unitNo" name="unitNo" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="country" class="col-sm-3 col-form-label">Country:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control textfield" id="country" name="country" placeholder="Please enter your country" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="email" class="col-sm-3 col-form-label">Email:</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control textfield" id="email" name="email" placeholder="Please enter your email" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="password" class="col-sm-3 col-form-label">Password: </label>
                <div class="col-sm-9">
                    <input class="form-control textfield" type="password" name="password" id="password" required/>
                    <span onclick="typeChange()" id="checkbox" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="password2" class="col-sm-3 col-form-label">Retype Password: </label>
                <div class="col-sm-9">
                    <input class="form-control textfield" type="password" name="password2" id="password2" required/>
                    <span onclick="typeChangeReset()" id="checkbox2" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="SecQns" class="col-sm-3 col-form-label">Security Question: </label>
                <div class="col-sm-9">
                    <select id="SecQns" name="SecQns" class="textfield form-control minimal" required>
                        <option value="" disabled selected>Select a question only you can answer</option>
                        <option value="Which polytechnic?">Which polytechnic?</option>
                        <option value="Wife's name?">Wife's name?</option>
                        <option value="How many brothers and sisters?">How many brothers and sisters?</option>
                    </select>
                    <span>*You are not allowed to edit this once submitted</span>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="SecAns" class="col-sm-3 col-form-label">Security Answer: </label>
                <div class="col-sm-9">
                    <input class="form-control textfield" type="test" name="SecAns" id="SecAns" placeholder="Please enter your answer" required/>
                    <span>*You are not allowed to edit this once submitted</span>
                </div>
            </div>
            <div align="center" style="margin-bottom:30px;" class="g-recaptcha" data-sitekey="6LezB0seAAAAALAeBQuG0QP8YRgD25D75TN-_xlB"></div>
            <button type="submit" class="center memberButton">Register</button>
        </form>
    </div>
</div>


<?php
    //Include the page layout header
    include("footer.php");
?>