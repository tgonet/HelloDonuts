<?php
    function checkUnique($email){
        include_once("mysql_conn.php");
        $qry = "SELECT Email FROM Shopper WHERE Email = ?";
        $stmt = $conn->prepare($qry);
        // "ssssss" - 6 string parameters
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            return false;
        }
        return true;
    }

    //Detext the current session
    session_start();

    //Include the page layout header
    include("header.php");
?>

<!-- <?php 
    echo "<script>
            function validateForm()
            {
                console.log('hi');
                if(document.register.password.value != document.register.password2.value){
                    alert('Passwords not match!');
                    return false;   // cancel submission
                }
                if(document.register.phone.value != ''){
                    var str = document.register.phone.value;
                    if(str.length != 8){
                        alert('Please enter a 8-digit phone number.');
                        return false;   //cancel submission
                    }
                    else if(str.substr(0,1) != '6' &&
                            str.substr(0,1) != '8' &&
                            str.substr(0,1) != '9'){
                        alert('Phone number in Singapore should start with 6,8 or 9.');
                        return false;   //cancel submission
                    }
                }";
                echo "document.cookie = 'email = ' + document.register.email.value";
                $test = checkUnique($_COOKIE["email"]);
                echo "if($test == false){
                    alert('Test');
                }
                return true;  // No error found
            }
    </script>"

?> -->

<div style="margin: 7em 0 7em 0; padding: 0">
    <div class="background" style="width: 80% !important">
        <form name="register" action="addMember.php" method="post" onsubmit="return validateForm()">
            <h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Register</h3>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="name" class="col-sm-3 col-form-label">Full Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control textfield" id="name" name="name" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="DOB" class="col-sm-3 col-form-label">Date of Birth:</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control textfield" id="DOB" name="DOB" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="DOB" class="col-sm-3 col-form-label">Address:</label>
                <div class="col-sm-9">
                    <textarea class="form-control textfield" id="address" name="address" rows="4" cols="50" placeholder="Please enter your address"  required></textarea>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="countryl" class="col-sm-3 col-form-label">Country:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control textfield" id="country" name="country" placeholder="Please enter your country" required>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="phone" class="col-sm-3 col-form-label">Phone:</label>
                <div class="col-sm-9">
                    <input type="tel" class="form-control textfield" id="phone" name="phone" placeholder="Please enter your mobile number" required>
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
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="password2" class="col-sm-3 col-form-label">Retype Password: </label>
                <div class="col-sm-9">
                    <input class="form-control textfield" type="password" name="password2" id="password2" required/>
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:40px;">
                <label for="SecQns" class="col-sm-3 col-form-label">Security Question: </label>
                <div class="col-sm-9">
                    <select id="SecQns" name="SecQns" class="textfield form-control minimal" required>
                        <option value="" disabled selected>Please choose a security question only you know the answer for</option>
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
            <button type="submit" class="center" style="padding: 10px 90px 10px 90px">Register</button>
        </form>
    </div>
</div>


<?php
    //Include the page layout header
    include("footer.php");
?>