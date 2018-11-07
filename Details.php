<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

$post_id = (int)mysqli_real_escape_string($conn,$_GET['post_id']);

$sql1="SELECT u.username, u.id as user_id, u.display_pic, p.image, p.id as post_id,
p.caption, p.creation_date_time, (
    SELECT count(id) FROM post_like
    WHERE post_like.post = {$post_id}
) as likes, (
    SELECT count(id) FROM post_like
    WHERE post_like.user = {$current_user_id} AND post_like.post = {$post_id}
) as liked
FROM post as p, user as u
WHERE p.user_id = u.id AND p.id = {$post_id}";

$result1 = mysqli_query($conn, $sql1);
if(!$result1){
    die("QUERY FAILED - " . mysqli_error($conn));
}


$sql2="SELECT u.username, c.text, u.id as user_id, c.id as comment_id, (
    SELECT count(id) FROM comment_like
    WHERE comment_like.comment = c.id
) as likes, (
    SELECT count(id) FROM comment_like
    WHERE comment_like.user = {$current_user_id} AND comment_like.comment = c.id
) as liked
FROM comment as c, user as u
WHERE c.post = $post_id AND c.user_id = u.id";

$result2 = mysqli_query($conn,$sql2);
if(!$result2){
    die("QUERY FAILED - " . mysqli_error($conn));
}

?>

<!doctype html>
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
    .navbar-brand{
        float: left;
    }
    .container-fluid{
        background-color: #f8f9fa !important;
        height: 91vh;
        padding-bottom: 63px;
    }
    .row{
        background-color: #f8f9fa !important;
    }
    .author-info{
        padding-bottom: 35px;
        margin-bottom: 10px;
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
        margin-top: 0px;
        text-decoration: none;
        color: black;
    }
    .postImage{
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        vertical-align: middle;
        margin: 0 auto;
    }
    .details{
        margin: 0 10px;
    }
    .details .detailsImage{
        margin: -10px;
    }
    .details img.like{
        float: right;
    }
    .comment{
        font-size: 0.85rem;
    }
    .author{
        font-size: 0.85rem;
        font-weight: bold;
        margin-right: 3px;
    }
    .details a{
        text-decoration: none;
        color: #212529;
    }
    .caption{
        margin-right: 30px;
        line-height: 1.15;
        font-size: 14px;
    }
    .dateText{
        margin-bottom: 7px;
    }
    .likecount{
        margin-right: 2px;
        float: right;
        margin-top: 2px;
    }
    .addComment{
        padding-bottom: 63px;
    }
    .likeccount{
        margin-right: 2px;
        float: right;
        margin-top: 2px;
    }
    </style>
    <title>Post Details</title>
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
        <div class="row">
            <div class = "offset-xs-2 col-xs-8 offset-md-4 col-md-4" style="padding: 0;">
                <br/>
                <?php
                if(mysqli_num_rows($result1)>0){
                    $row = mysqli_fetch_assoc($result1);
                    if($row['liked']==0){
                        $like_icon = "assets/images/PicturesqueLike.png";
                        $like_class = "like";
                    }
                    else{
                        $like_icon = "assets/images/Liked.png";
                        $like_class = "like liked";
                    }
                    $formatted_date = date_format(date_create($row['creation_date_time']), "M d, Y");
                    echo "
                    <div class='details'>
                        <div class='author-info'>
                            <img class='card-profile-pic' src='{$row['display_pic']}'/>
                            <a href='Profile.php?username={$row['username']}'><p class='card-text authorname'>{$row['username']}</p></a>
                        </div>
                        <div class='detailsImage' style='text-align: center;'>
                            <img class='postImage' src='{$row['image']}' alt='Card image cap'>
                        </div>
                        <br>
                        <img class='{$like_class}' id='{$row['post_id']}' src='{$like_icon}' height='30px' width='30px' style='float: right'>
                        <p class='text-muted likecount'>{$row['likes']}</p>
                        <a href='Profile.php?username={$row['username']}'><span class='card-text' style='font-weight: bold'>{$row['username']}</span></a><br>
                        <span class='caption text-muted'>{$row['caption']}</span>
                         <p class='card-text dateText'><small class='text-muted'>{$formatted_date}</small></p>
                        <br>
                        <span class=\"card-text text-muted\">Comments:</span><br><br>
                        ";
                }
                if(mysqli_num_rows($result2)>0){
                    while($row=mysqli_fetch_assoc($result2)){
                        if($row['liked']==0){
                            $like_icon = "assets/images/PicturesqueLike.png";
                            $like_class = "comment like";
                        }
                        else{
                            $like_icon = "assets/images/Liked.png";
                            $like_class = "comment like liked";
                        }
                        echo"
                        <img class='{$like_class}' id='{$row['comment_id']}' src='{$like_icon}' height='25px' width='25px' style='float: right'>
                        <p class='text-muted likeccount'>{$row['likes']}</p>
                        <a href=''><span class='card-text text-muted author'>{$row['username']}</span></a><span class='card-text text-muted comment'>{$row['text']}</span><br>
                        <hr/>
                        ";
                    }
                }
                ?>
                    <div class="addComment" style="">
                        <form class="row" id="postComment" style="margin: 0" method="post" action="CommentKaro.php?post_id=<?php echo $post_id ?>">
                            <div class="form-group" style="float: left; width: 80%; margin-bottom: 0;">
                                <input type="text" class="form-control" id="id_comment" name="comment" placeholder="Add comment" minlength="1" oninvalid="this.setCustomValidity('Comment cannot be blank.')" oninput="this.setCustomValidity('')" required>
                            </div>
                            <button type="submit" class="btn btn-outline-success loginButton" style="float: right; width: 20%;">Post</button>
                        </form>
                        <br>
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
    <script>
    $(document).ready(function(){
        $('.like').click(function(e){
            if($(this).hasClass('comment')){
                var comment_id = $(this).attr('id');
                var url = "LikeKaro.php?comment_id="+comment_id;
                if($(this).hasClass('liked')){
                    $(this).attr('src', 'assets/images/PicturesqueLike.png').removeClass('liked');
                    $(this).next('.likeccount').text((parseInt($(this).next('.likeccount').text())-1));
                }
                else {
                    $(this).attr('src', 'assets/images/Liked.png').addClass('liked');
                    $(this).next('.likeccount').text((parseInt($(this).next('.likeccount').text())+1));
                }
            }
            else{
                var post_id = $(this).attr('id');
                var url = "LikeKaro.php?post_id="+post_id;
                if($(this).hasClass('liked')){
                    $(this).attr('src', 'assets/images/PicturesqueLike.png').removeClass('liked');
                    $(this).next('.likecount').text((parseInt($(this).next('.likecount').text())-1));
                }
                else {
                    $(this).attr('src', 'assets/images/Liked.png').addClass('liked');
                    $(this).next('.likecount').text((parseInt($(this).next('.likecount').text())+1));
                }
            }
            $.getJSON(url, function(result) {
                //Chill hai bois. Paani Puri joye chhe.
            });
        });
    });
    </script>
</body>
</html>
