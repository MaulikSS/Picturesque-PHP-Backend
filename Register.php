<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

$is_successful = -1;

if(isset($_SESSION['username'])){
    redirect('Home.php');
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
        $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
        $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $password = mysqli_real_escape_string($conn, password_hash(trim($_POST['password']), PASSWORD_DEFAULT));
        date_default_timezone_set('Asia/Calcutta');
        $date = date('Y-m-d H:i:s');

        $is_successful = register_user($first_name, $last_name, $username, $email, $password, $date);
        if($is_successful==0){
            redirect('Login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
    .navbar.navTop{
        font-weight: lighter;
        padding-top: 15px;
    }
    .navbar.navBottom{
        font-weight: lighter;
        padding-bottom: 2px;
    }
    .navBottom .navbar-nav{
        width: 100%;
        margin: 0 auto;
    }
    .navBottom .navbar-nav .nav-item{
        width: 20%;
    }
    .navBottom .navbar-nav .nav-item .nav-link{
        text-align: center;
    }
    .navbar-toggler{
        float: right;
    }
    .navbar-brand{
        float: left;
    }
    .container-fluid{
        background-color: #f8f9fa !important;
        height: 91vh;
        padding-bottom: 56px;
    }
    .row{
        background-color: #f8f9fa !important;
    }
    .error{
        /*display: none;*/
        color: red !important;
    }
    </style>
    <title>Register</title>
    <link rel="shortcut icon" href="assets/images/PicturesqueLogo.png" type="image/png">
</head>
<body>
    <nav class="navbar navTop sticky-top navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="./Home.php">
            <img src="assets/images/PicturesqueLogo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            &nbsp;Picturesque
        </a>
    </nav>
    <div class="container-fluid">
        <div class = "row">
            <div class = "offset-xs-2 col-xs-8 offset-md-4 col-md-4">
                <br/>
                <form id="registerForm" action="" method="POST">
                    <div class="form-group">
                        <label for="Id_First_Name">First Name</label>
                        <input type="text" class="form-control" id="id_first_name" name="first_name" aria-describedby="firstname" placeholder="First Name"
                        value="<?php if($is_successful==1 || $is_successful==2){ echo $first_name; } ?>" minlength="2" oninvalid="this.setCustomValidity('First name should be minimum 2 characters')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Last_Name">Last Name</label>
                        <input type="text" class="form-control" id="id_last_name" name="last_name" aria-describedby="lastname" placeholder="Last Name"
                        value="<?php if($is_successful==1 || $is_successful==2){ echo $last_name; } ?>" minlength="2" oninvalid="this.setCustomValidity('Last name should be minimum 2 characters')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Username">Username</label>
                        <small class="text-muted error usernameError">&nbsp;<?php if($is_successful==1){ echo "Username is already taken."; } ?></small>
                        <input type="text" class="form-control" id="id_username" name="username" aria-describedby="username" placeholder="Username"
                        value="<?php if($is_successful==1 || $is_successful==2){ echo $username; } ?>" minlength="3" maxlength="16" oninvalid="this.setCustomValidity('Username should be between 3 and 16 characters')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Email">Email address</label>
                        <small class="text-muted error emailError">&nbsp;<?php if($is_successful==2){ echo "Email is already in use."; } ?></small>
                        <input type="email" class="form-control" id="id_email" name="email" aria-describedby="email" placeholder="Enter email"
                        value="<?php if($is_successful==1 || $is_successful==2){ echo $email; } ?>" oninvalid="this.setCustomValidity('Please enter a valid email.')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Password">Password</label>
                        <small class="text-muted error passwordError">&nbsp;</small>
                        <input type="password" class="form-control" id="id_password" name="password" placeholder="Password" pattern="^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*()]).{8,20}" minlength="8" maxlength="20" oninvalid="this.setCustomValidity('Password should be between 8 and 20 characters and must contain at least one uppercase, one lowercase, one digit and one special character')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <button type="submit" class="btn btn-success loginButton">&nbsp;&nbsp;Register&nbsp;&nbsp;</button>
                    <br/><br/>
                    <small id="emailHelp" class="form-text text-muted" style="padding-bottom: 5px;">Already registered?</small>
                    <a class="btn btn-primary" href="./Login.php">&nbsp;&nbsp;&nbsp;&nbsp;Login&nbsp;&nbsp;&nbsp;&nbsp;</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
