<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

/*
1 -> followers
2 -> following
0 -> requests
*/

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['option']) && ($_POST['option']>=0) && ($_POST['option']<=2)){
        $option = mysqli_real_escape_string($conn, $_POST['option']);

        $sql_pending_requests = "SELECT u.id, u.username, u.display_pic FROM request as r, user as u
        WHERE r.receiver = {$current_user_id} AND r.accepted = 0 AND r.rejected = 0 AND u.id = r.sender
        ORDER BY creation_date_time DESC";

        $sql_followers = "SELECT u.username, u.display_pic FROM follow as f, user as u
        WHERE f.followee = {$current_user_id} AND f.follower = u.id AND f.follower != {$current_user_id}
        ORDER BY u.username";

        $sql_following = "SELECT u.username, u.display_pic FROM follow as f, user as u
        WHERE f.follower = {$current_user_id} AND f.followee = u.id AND f.followee != {$current_user_id}
        ORDER BY u.username";

        $sql = array($sql_pending_requests, $sql_followers, $sql_following);
        global $conn;
        $result = mysqli_query($conn, $sql[$option]);
        if(!$result){
            die("QUERY FAILED - ".mysqli_error($conn));
        }
        $resulthtml = "";
        while($row = mysqli_fetch_assoc($result)){
            if($option==0){
                $resulthtml.="<li class='list-group-item'><img class='profilepic' src='{$row['display_pic']}'/><a href='Profile.php?username={$row['username']}'>{$row['username']}</a><button type='button' id={$row['id']} class='btn btn-outline-danger rejectRequest'><i class='material-icons'>close</i></button><button type='button' id={$row['id']} class='btn btn-outline-success acceptRequest'><i class='material-icons'>check</i></button></li>";
            }
            else{
                $resulthtml.="<li class='list-group-item'><img class='profilepic' src='{$row['display_pic']}'/><a href='Profile.php?username={$row['username']}'>{$row['username']}</a></li>";
            }
        }
        echo ($resulthtml);
        exit();
    }
    else{
        exit();
    }
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
            height: 91vh;
            padding-bottom: 56px;
        }
        .row{
            background-color: #f8f9fa !important;
        }
        .card{
             width: 100%;
        }
        .profilepic{
            width: 35px;
            height: 35px;
            float: left;
            display: block;
            border-radius: 15px;
        }
        .list-group>li.list-group-item{
            width: 100%;
            text-align: left;
            padding: 0px 20px;
        }
        .list-group>li.list-group-item>img{
            margin: 12px 0px;
        }
        .list-group>li.list-group-item>a{
            width: 60%;
            text-decoration: none;
            color: black;
            padding: 12px 20px;
            display: block;
            float: left;
            font-size: 20px;
            font-weight: 500;
        }
        .list-group-item button{
            border-radius: 15px;
            padding: 2px;
            margin-top: 15px;
            margin-left: 10px;
            width: 30px;
            height: 30px;
            float: right;
        }
        .tabs{
            width: 100%;
            text-align: center;
            display: block;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        .tab-link{
            width: 32%;
            display: inline-block;
            padding: 15px 0px;
            font-family: 'Quicksand', sans-serif;
            font-size: 15px;
        }
        .tab-link.active{
            background-color: #ddd;
        }
        </style>
        <title>Requests</title>
        <link rel="shortcut icon" href="assets/images/PicturesqueLogo.png" type="image/png">
    </head>
    <body>
        <nav class="navbar navTop sticky-top navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="./Home.php">
                <img src="assets/images/PicturesqueLogo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                &nbsp;Picturesque
            </a>
            <form class="form-inline my-lg-0" action="Logout.php">
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
            <div class = "row">
                <div class = "col-xs-12 offset-md-4 col-md-4">
                    <div class="tab-container">
                        <div class="tabs">
                            <a class="tab-link active" data-option=0>REQUESTS</a>
                            <a class="tab-link" data-option=1>FOLLOWERS</a>
                            <a class="tab-link" data-option=2>FOLLOWING</a>
                        </div>
                        <br>
                        <div class="">
                            <div class="card">
                                <ul class="list-group list-group-flush results">

                                </ul>
                            </div>
                        </div>
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
        <script type="text/javascript">
            $('.tab-link').click(function(){
                $(this).siblings('.tab-link').removeClass('active');
                $(this).addClass('active');
                var optionvalue = $(this).attr('data-option');
                $.post('Requests.php', {option: optionvalue}, function(response){
                    $('.results').html(response);
                    $('.btn').click(function(){
                        var id = parseInt($(this).attr('id'));
                        if($(this).hasClass('acceptRequest')){
                            $.post('Follow.php', {user_id: id, decision: 'accept'}, function(response){
                                window.location.href = 'Requests.php';
                            });
                        }
                        else if($(this).hasClass('rejectRequest')){
                            $.post('Follow.php', {user_id: id, decision: 'reject'}, function(response){
                                window.location.href = 'Requests.php';
                            });
                        }
                    });
                });
            });
            $(document).ready(function(){
                $.post('Requests.php', {option: 0}, function(response){
                    $('.results').html(response);
                });
                setTimeout(function() {
                    $('.btn').click(function(){
                        var id = parseInt($(this).attr('id'));
                        if($(this).hasClass('acceptRequest')){
                            $.post('Follow.php', {user_id: id, decision: 'accept'}, function(response){
                                window.location.href = 'Requests.php';
                            });
                        }
                        else if($(this).hasClass('rejectRequest')){
                            $.post('Follow.php', {user_id: id, decision: 'reject'}, function(response){
                                window.location.href = 'Requests.php';
                            });
                        }
                    });
                }, 100);
            });
        </script>
    </body>
</html>
