<?php
    //Detect the current session
    session_start();

    if (!isset($_SESSION["ShopperID"])) {  
        // redirect to login page if there is no shopper id in session
        header ("Location: login.php");
        exit;
    }

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
        if(document.editProfile.phone.value != ""){
            e.preventDefault();
            var str = document.editProfile.phone.value;
            console.log(str.length);
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
            $.ajax({
                url:'updateProfile.php',
                type:'POST',
                data: $("#ProfileForm").serialize(),
                success: function(response){
                    console.log(response);
                    if(response =="success"){
                        alert("Profile Updated.");
					    window.location.href = "index.php";
                    }
                    else if(response == "This email is taken. Please use another email."){
                        alert(response);
                    }
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
            }
            });
        }
        
    }
</script>
<?php 
    // Include the PHP file that establishes database connection handle: $conn
	include_once("mysql_conn.php");
    if(isset($_SESSION["ShopperID"])){
        // Define thge INSERT SQL statement
        $qry = "SELECT Name, Email, Address, BirthDate, Country, Phone FROM Shopper WHERE ShopperID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $_SESSION["ShopperID"]);

        // To Do 1 (Practical 2): Validate login credentials with database

        if ($stmt->execute()) {  // SQL statement executed successfully

            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_array();
            $phoneNo = str_replace("(65) ", "" , $row["Phone"]);
            echo "<div style='margin: 7em 0 7em 0; padding: 0'>
            <div class='background' style='width: 60% !important'>
                <form id='ProfileForm' style='margin: auto' name='editProfile' action='#' method='post' onsubmit='validateForm(event)'>
                    <h3 align='center' style='margin: 0 0 40px 0; font-weight: 600;color:black;'>Edit Profile</h3>
                    <div class='form-group row' style='margin-bottom:40px;'>
                        <label for='name' class='col-sm-3 col-form-label'>Full Name:</label>
                        <div class='col-sm-9'>
                            <input type='text' class='form-control textfield' id='name' name='name' value='$row[Name]' required>
                        </div>
                    </div>
                    <div class='form-group row' style='margin-bottom:40px;'>
                        <label for='bday' class='col-sm-3 col-form-label'>Date of Birth:</label>
                        <div class='col-sm-9'>
                            <input type='date' class='form-control textfield' id='bday' name='bday' min='1900-01-01' onclick='getDate()' value='$row[BirthDate]' required>
                        </div>
                    </div>
                    <div class='form-group row' style='margin-bottom:40px;'>
                        <label for='address' class='col-sm-3 col-form-label'>Address:</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control textfield' id='address' name='address' rows='4' cols='50' placeholder='Please enter your address' required>$row[Address]</textarea>
                        </div>
                    </div>
                    <div class='form-group row' style='margin-bottom:40px;'>
                        <label for='country' class='col-sm-3 col-form-labe'>Country:</label>
                        <div class='col-sm-9'>
                            <input type='text' class='form-control textfield' id='country' name='country' placeholder='Please enter your country' value='$row[Country]' required>
                        </div>
                    </div>
                    
                    <div class='form-group row' style='margin-bottom:40px;'>
                        <label for='phone' class='col-sm-3 col-form-label'>Phone:</label>
                        <div class='col-sm-9'>
                            <div class='input-box'>
                                <span class='prefix'>+65</span>
                                <input type='tel' class='form-control textfield' id='phone' name='phone' placeholder='Please enter your mobile number' value='$phoneNo' required>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row' style='margin-bottom:40px;'>
                        <label for='email' class='col-sm-3 col-form-label'>Email:</label>
                        <div class='col-sm-9'>
                            <input type='email'class='form-control textfield' id='email' name='email' placeholder='Please enter your email' value='$row[Email]' required>
                        </div>
                    </div>
                    <button type='submit' class='center' style='padding: 10px 90px 10px 90px'>Save</button>
                    <p align='center' style='font-weight:bold;font-size:20px;'><a style='color:black;' href='changePassword.php'>Change Password</a></p>
                </form>
            </div>
        </div>

        </div>";
        }
    }
?>




<?php
    //Include the page layout header
    include("footer.php");
?>

