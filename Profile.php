<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

if($_SERVER['REQUEST_METHOD']=='GET') {
    global $conn;
    if (isset($_GET['username'])) {
        $username = mysqli_real_escape_string($conn, $_GET['username']);
    } else {
        $username = $current_username;
    }
    if ($username == $current_username) {
        $opt = 0; // self-profile
    } else {
        $sql = "SELECT count(*) FROM follow WHERE follower = {$current_user_id} and followee=(SELECT id from user where username='{$username}')";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("QUERY FAILED - " . mysqli_error($conn));
        }
        $row = mysqli_fetch_assoc($result);
        if ($row['count(*)'] == 0) {
            $sql = "SELECT * FROM request WHERE sender={$current_user_id} AND receiver=(SELECT id from user where username='{$username}') AND accepted=0 AND rejected=0";
            $result = mysqli_query($conn, $sql);
            if(!$result) {
                die("QUERY FAILED 1 - " . mysqli_error($conn));
            }
            else if(mysqli_num_rows($result)==0){
                $opt = 1; //Not following profile.
            }
            else if(mysqli_num_rows($result)>=0){
                $opt = 3; //Follow request pending.
            }
        } else {
            $opt = 2; // Following profile.
        }
    }
}
$user_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id from user where username='{$username}'"));

$sql1 = "SELECT u.username, u.display_pic, u.bio, u.first_name, u.last_name FROM user_show AS u WHERE u.username='{$username}'";

$sql2 = "SELECT p.id AS post_id, p.image FROM post AS p WHERE p.user_id={$user_id['id']} ORDER BY p.creation_date_time DESC";

$result1 = mysqli_query($conn, $sql1);
if (!$result1) {
    die("QUERY FAILED 2 - " . mysqli_error($conn));
}
$result1=mysqli_fetch_assoc($result1);

$result2 = mysqli_query($conn, $sql2);
if (!$result2) {
    die("QUERY FAILED - " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand|Raleway" rel="stylesheet">
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
                min-height: 91vh;
                padding-bottom: 75px;
            }
            .row{
                background-color: #f8f9fa !important;
            }
            .display_pic{
                width: 80px;
                height: 80px;
                border-radius: 40px;
                margin: 0 auto;
            }
            .header{
                padding-top: 8%;
                padding-left: 6%;
            }
            .header-right{
                vertical-align: 15px;
                font-size: 20px;
                font-weight: 500;
                text-align: center;
                float: right;
                width: 70%;
            }
            .header-img{
                float: left;
                width: 30%;
            }
            .header-button{
                margin-top: 10px;
            }
            .follow, .unfollow, .updateProfile, .cancelRequest{
                width: 70%;
                align-self: center;
                padding: 4px 12px;
                font-style: italic;
            }
            .header-text{
                margin-top: 100px;
                padding-right: 6%;
            }
            .full-name{
                font-size: 18px;
                font-weight: bold;
                text-align: left;
            }
            .bio{
                text-align: left;
                font-family: 'Quicksand', sans-serif;
                font-weight: 500;
                font-size: 17px;
            }
            .errorText{
                margin: 0 auto;
                font-family: 'Quicksand', sans-serif;
                font-weight: 500;
                font-size: 17px;
            }
            .profilepageimages{
                padding: 0 15px;
            }
            .profilepageimages .col-xs-4{
                width: 32%;
                display: flex;
                align-items: center;
                align-content: flex-start;
                justify-content: center;
                height: 125px;
                overflow: hidden;
                margin: 2px;
            }
            .profilepageimages .col-xs-4 img{
                display: block;
                height: 100% !important;
                margin: 0 auto;
            }
        </style>
        <title>Profile</title>
        <link rel="shortcut icon" href="assets/images/PicturesqueLogo.png" type="image/png">
    </head>
    <body>
        <nav class="navbar navTop sticky-top navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="./Home.php">
                <img src="assets/images/PicturesqueLogo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                &nbsp;Picturesque
            </a>
            <form class="form-inline my-2 my-lg-0" action="Logout.php">
                <?php
                if(!isset($_SESSION['username'])){
                    echo '<button class="btn btn-outline-success my-sm-0" type="submit" style="width: 100%">Login</button>';
                }
                else{
                    echo '<button class="btn btn-outline-info my-sm-0" type="submit" style="width: 100%">Logout</button>';
                }
                ?>
            </form>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="offset-xs-2 col-xs-8 offset-md-4 col-md-4 maincontainer">
                    <div class="profile">
                        <div class="header">
                            <div class="header-img">
                                <img class="display_pic" src="<?php echo $result1['display_pic'] ?>" alt="">
                            </div>
                            <div class="header-right">
                                <span class="username"><?php echo $result1['username'] ?></span><br/>
                                <div class="header-button">
                                    <?php
                                        if($opt==0){
                                            echo "<button type=\"button\" class=\"btn btn-outline-success updateProfile\">Update Profile</button>";
                                        }
                                        else if($opt==1){
                                            echo "<button type=\"button\" class=\"btn btn-primary follow\">Follow</button>";
                                        }
                                        else if($opt==2){
                                            echo "<button type=\"button\" class=\"btn btn-outline-secondary unfollow\">Unfollow</button>";
                                        }
                                        else if($opt==3){
                                            echo "<button type=\"button\" class=\"btn btn-outline-secondary cancelRequest\">Cancel Request</button>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="header-text">
                                <span class="full-name"><?php echo $result1['first_name'].' '.$result1['last_name'] ?></span><br>
                                <span class="bio"><?php echo $result1['bio'] ?></span>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row profilepageimages">
                        <?php
                            if($opt==1 || $opt==3){
                                echo "<span class=\"errorText\">You aren't following the user.</span>";
                            }
                            else{
                                if(mysqli_num_rows($result2)==0){
                                    echo "<span class=\"errorText\">No Posts to display.</span>";
                                }
                                else{
                                    while($row2=mysqli_fetch_assoc($result2)){
                                        echo "
                                        <div class=\"col-xs-4\">
                                            <img src=\"{$row2['image']}\" id=\"{$row2['post_id']}\">
                                        </div>
                                        ";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navBottom fixed-bottom navbar-dark bg-dark navbar-expand">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Home.php">
                        <i class="material-icons md-light">home</i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Search.php">
                        <i class="material-icons md-light">search</i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Upload.php">
                        <i class="material-icons md-light">add_box</i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Requests.php">
                        <i class="material-icons md-light">supervisor_account</i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Profile.php">
                        <i class="material-icons md-light">account_circle</i>
                    </a>
                </li>
            </ul>
        </nav>
        <script>
            $('.profilepageimages .col-xs-4').on('click', function(){
                var id = parseInt($(this).children('img').attr('id'));
                window.location.href = 'Details.php?post_id='+id;
            });
            $(".follow, .updateProfile, .unfollow, .cancelRequest").on('click', function(){
                var option;
                var username = $('.username').text();
                if($(this).hasClass('follow')){
                    option = 'follow';
                }
                else if($(this).hasClass('unfollow')){
                    option = 'unfollow';
                }
                else if($(this).hasClass('cancelRequest')){
                    option = 'cancelRequest';
                }
                else if($(this).hasClass('updateProfile')){
                    window.location.href = 'UpdateProfile.php';
                }
                $.post('FollowKaro.php',
                    {option: option, username: username},
                    function(response){
                        if(response==true)
                            window.location.href = 'Profile.php?username='+username;
                    }
                );
            });
        </script>
    </body>
</html>
