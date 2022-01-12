<style>
.background{
    padding: 40px 0 40px 0;
    width: 70%;
    background: #CAF0F8;
    border-radius: 30px;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    border-radius:30px; 
    box-shadow:0.8em 0.8em #FCDDEC
}

.maxWidth{
    width: 60%;
}

.center{
    display: block;
    margin: auto;
}

form{
    vertical-align: middle;
    margin: auto; 
    width:75%;
    padding:30px;
}

button{
    border-radius: 10px  !important;
    background: #63200D;
    border: none;
    color: white;
}

.textfield{
    border-radius : 20px !important;
    border: 0 solid #FFFF !important;
}
</style>

<?php
    //Detext the current session
    session_start();

    //Include the page layout header
    include("header.php");
?>

<div class="background">
    <form >
        <h3 align="center" style="margin: 0 0 40px 0; font-weight: 600;color:black;">Login</h3>
        <div class="form-group row" style="margin-bottom:40px;">
            <label for="email" class="col-sm-2 col-form-label">Email:</label>
            <div class="col-sm-1">
            </div>
            <div class="col-sm-9">
                <input type="email" class="form-control textfield" id="email" name="email" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-1">
            </div>
            <div class="col-sm-9">
                <input class="form-control textfield" type="password" name="password" id="password" required/>
            </div>
        </div>
        <p align="right" style="margin-top: -15px">Forget Password</p>
        <button type="button" class="center" style="padding: 10px 100px 10px 100px">Time to get that dough!</button>
        <p align="center" style="padding:10px;">New User?<a href="https://www.w3schools.com" style="font-weight: 600;color:black"> Register Here</a></p>
    </form>
</div>

<?php
    //Include the page layout header
    include("footer.php");
?>