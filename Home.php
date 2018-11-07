<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

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
        min-height: 91vh;
        padding-bottom: 56px;
    }
    .row{
        background-color: #f8f9fa !important;
    }
    .card-deck{
        margin: auto;
    }
    .card{
        width: 100%;
        margin: 10px auto;
        box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.15);
    }
    .card-header{
        background-color: white;
    }
    .card-profile-pic{
        width: 30px;
        height: 30px;
        border-radius: 15px;
        margin-right: 10px;
        float: left;
    }
    .authorname{
        font-weight: bold;
        float: left;
        margin-top: 3px;
        text-decoration: none;
        color: black;
    }
    .card-img-top{
        min-height: 100%;
        min-width: 100%;
        max-height: 100%;
        min-width: 100%;
        text-align: center;
        display: inline-block;
    }
    .postImage{
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        vertical-align: middle;
        margin: 0 auto;
    }
    .card-body a{
        text-decoration: none;
        color: #212529;
    }
    .likecount{
        margin-right: 2px;
    }
    .caption{
        margin-right: 30px;
        line-height: 1.15;
        font-size: 14px;
    }
    .dateText{
        margin-bottom: 7px;
    }
    </style>
    <title>Home</title>
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
                echo '<button class="btn btn-outline-success my-sm-0" type="submit" style="width: 100%>Login</button>';
            }
            else{
                echo '<button class="btn btn-outline-info my-sm-0" type="submit" style="width: 100%">Logout</button>';
            }
            ?>
        </form>
    </nav>
    <div class="container-fluid">
        <div class = "row">
            <div class = "offset-xs-2 col-xs-8 offset-md-4 col-md-4 maincontainer">


                <!-- Space for main content to be loaded -->


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
        var start = 0;
        var limit = 5;
        var reachedMax = false;

        function getData() {
            if (reachedMax)
                return;
            $.ajax({
               url: 'HomeData.php',
               method: 'POST',
               data: {
                   getData: 1,
                   start: start,
                   limit: limit
               },
               success: function(response) {
                    if (response == "reachedMax")
                        reachedMax = true;
                    else {
                        start += limit;
                        $(".maincontainer").append(response);
                    }
                }
            });
        }

        $(window).scroll(function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height())
                getData();
        });

        $(document).ready(function(){
            getData();
            setTimeout(function() {
                $('.like').click(function(e){
                    var post_id = $(this).attr('id');
                    var url = "LikeKaro.php?post_id="+post_id;
                    $.getJSON(url, function(result) {
                        //Just inform the server about the like. No response handling required.
                    });
                    if($(this).hasClass('liked')){
                        $(this).attr('src', 'assets/images/PicturesqueLike.png').removeClass('liked');
                        $(this).next('.likecount').text((parseInt($(this).next('.likecount').text())-1));
                    }
                    else {
                        $(this).attr('src', 'assets/images/Liked.png').addClass('liked');
                        $(this).next('.likecount').text((parseInt($(this).next('.likecount').text())+1));
                    }
                });
            }, 200);
        });
    </script>
</body>
</html>
