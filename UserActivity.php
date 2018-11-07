<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['username'])){
    redirect('Login.php');
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
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
    .maincontainer{
        padding: 0;
    }
    #chartContainer{
        position: relative;
        top: 20%;
        height: 300px;
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
    <script>
        window.onload = function () {
            $.ajax({
                url: 'UserActivityAPI.php',
                method: 'POST',
                data: {
                    getActivity: 1,
                    limit: 5
                },
                success: function(response) {
                    plotGraph(JSON.parse(response));
                }
            });
            function plotGraph(data){
                console.log(data);
                var chart = new CanvasJS.Chart("chartContainer", {
                	animationEnabled: true,
                	exportEnabled: true,
                	title:{
                		text: "Picturesque Top Users"
                	},
                	axisY:{
                		title: "Number"
                	},
                	toolTip: {
                		shared: true
                	},
                	legend:{
                		cursor:"pointer",
                		itemclick: toggleDataSeries
                	},
                	data: [{
                		type: "spline",
                		name: data[0].username,
                		showInLegend: true,
                		dataPoints: [
                			{ label: "Posts" , y: parseInt(data[0].posts_published) },
                			{ label: "Comments", y: parseInt(data[0].comments_made) },
                			{ label: "Post Likes", y: parseInt(data[0].posts_liked) },
                			{ label: "Comment Likes", y: parseInt(data[0].comments_liked) }
                		]
                	},
                	{
                		type: "spline",
                		name: data[1].username,
                		showInLegend: true,
                		dataPoints: [
                            { label: "Posts" , y: parseInt(data[1].posts_published) },
                			{ label: "Comments", y: parseInt(data[1].comments_made) },
                			{ label: "Post Likes", y: parseInt(data[1].posts_liked) },
                			{ label: "Comment Likes", y: parseInt(data[1].comments_liked) }
                		]
                	},
                	{
                		type: "spline",
                		name: data[2].username,
                		showInLegend: true,
                		dataPoints: [
                            { label: "Posts" , y: parseInt(data[2].posts_published) },
                			{ label: "Comments", y: parseInt(data[2].comments_made) },
                			{ label: "Post Likes", y: parseInt(data[2].posts_liked) },
                			{ label: "Comment Likes", y: parseInt(data[2].comments_liked) }
                		]
                	},
                	{
                		type: "spline",
                		name: data[3].username,
                		showInLegend: true,
                		dataPoints: [
                            { label: "Posts" , y: parseInt(data[3].posts_published) },
                			{ label: "Comments", y: parseInt(data[3].comments_made) },
                			{ label: "Post Likes", y: parseInt(data[3].posts_liked) },
                			{ label: "Comment Likes", y: parseInt(data[3].comments_liked) }
                		]
                	},
                	{
                		type: "spline",
                		name: data[4].username,
                		showInLegend: true,
                		dataPoints: [
                            { label: "Posts" , y: parseInt(data[4].posts_published) },
                			{ label: "Comments", y: parseInt(data[4].comments_made) },
                			{ label: "Post Likes", y: parseInt(data[4].posts_liked) },
                			{ label: "Comment Likes", y: parseInt(data[4].comments_liked) }
                		]
                	}]
                });

                chart.render();

                function toggleDataSeries(e) {
                	if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                		e.dataSeries.visible = false;
                	}
                	else {
                		e.dataSeries.visible = true;
                	}
                	chart.render();
                }
            }
        }
    </script>
    <title>User Activity</title>
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
            <div class="maincontainer col-xs-12 offset-md-3 col-md-6">
                <br>
                <div id="chartContainer">

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

    </script>
</body>
</html>
