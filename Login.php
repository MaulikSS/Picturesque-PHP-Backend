<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

$is_successful = -1;

if(isset($_SESSION['id'])){
    redirect('Home.php');
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $password = mysqli_real_escape_string($conn, trim($_POST['password']));

        $is_successful = login_user($username, $password);
        if($is_successful==0){
            redirect('Home.php');
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
        margin-left: 5px;
        color: red !important;
    }
    </style>
    <title>Login</title>
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
                <form id="loginForm" action="" method="POST">
                    <div class="form-group">
                        <label for="Id_Username">Username</label>
                        <small class="text-muted error emailError">&nbsp;<?php if($is_successful==2){ echo "User does not exist."; } ?></small>
                        <input type="text" class="form-control" id="id_username" name="username" aria-describedby="Username" placeholder="Username" value="<?php if($is_successful==1){ echo $username; } ?>" oninvalid="this.setCustomValidity('Username cannot be blank.')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Password">Password</label>
                        <small class="text-muted error passwordError">&nbsp;<?php if($is_successful==1){ echo "The password is incorrect."; } ?></small>
                        <input type="password" class="form-control" id="id_password" name="password" placeholder="Password" oninvalid="this.setCustomValidity('Password cannot be left blank.')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <button type="submit" class="btn btn-success loginButton">&nbsp;&nbsp;Login&nbsp;&nbsp;</button>
                    <br/><br/>
                    <small id="emailHelp" class="form-text text-muted" style="padding-bottom: 5px;">Not yet registered?</small>
                    <a class="btn btn-primary" href="./Register.php">Register</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
