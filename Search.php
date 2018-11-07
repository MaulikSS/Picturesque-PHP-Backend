<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['searchstring'])){
        $searchstring = mysqli_real_escape_string($conn, $_POST['searchstring']);
        if(!$searchstring==''){
            $sql = "(SELECT username, display_pic FROM user WHERE username LIKE '".$searchstring."%' ORDER BY username) UNION (SELECT username, display_pic FROM user WHERE username LIKE '%".$searchstring."%' ORDER BY username)";
            global $conn;
            $result = mysqli_query($conn, $sql);
            if(!$result){
                die("QUERY FAILED - ".mysqli_error($conn));
            }
            $resulthtml = "";
            while($row = mysqli_fetch_assoc($result)){
                $resulthtml.="<li class='list-group-item'><img class='profilepic' src='{$row['display_pic']}'/><a href='Profile.php?username={$row['username']}'>{$row['username']}</a></li>";
            }
            echo ($resulthtml);
            exit();
        }
        else{
            exit();
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
        width: 87%;
        text-decoration: none;
        color: black;
        padding: 12px 20px;
        display: block;
        float: left;
        font-size: 20px;
        font-weight: 500;
    }
    </style>
    <title>Search</title>
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
            <div class = "offset-xs-2 col-xs-8 offset-md-4 col-md-4">
                <br>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">@</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search username" aria-label="Username" aria-describedby="basic-addon1" name="searchstring" autofocus>
                </div>
                <div class="">
                    <div class="card">
                        <ul class="list-group list-group-flush results">

                        </ul>
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
        $('input[name=searchstring]').keyup(function(){
            var searchstringvalue = $(this).val();
            $.post('Search.php', {searchstring: searchstringvalue}, function(result){
                $('.results').html(result);
            });
        });
    </script>
</body>
</html>
