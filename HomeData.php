<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['getData']) && ($_POST['getData']==='1') && isset($_POST['start']) && isset($_POST['limit'])){
        global $conn;
        $start = (int)mysqli_real_escape_string($conn, $_POST['start']);
        $limit = (int)mysqli_real_escape_string($conn, $_POST['limit']);

        $sql = "SELECT u.id as user_id, u.username, u.display_pic, p.id as post_id,
        p.image, p.caption, p.creation_date_time, (
            SELECT count(*) FROM post_like
            WHERE post_like.post = p.id
        ) as likes, (
            SELECT count(*) FROM comment
            WHERE comment.post = p.id
        ) as comments, (
            SELECT count(*) FROM post_like
            WHERE post_like.user = '{$current_user_id}' AND post_like.post = p.id
        ) as liked
        FROM post as p, (
            SELECT user.username, user.display_pic, user.id
            FROM follow as f, user
            WHERE f.follower = '{$current_user_id}' AND f.followee = user.id
        ) as u
        WHERE p.user_id = u.id
        ORDER BY p.creation_date_time DESC
        LIMIT {$start}, {$limit}";

        $result = mysqli_query($conn, $sql);
        if(!$result){
            die("QUERY FAILED - " . mysqli_error($conn));
        }

        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)) {
                if($row['liked']==0){
                    $like_icon = "assets/images/PicturesqueLike.png";
                    $like_class = "like";
                }
                else{
                    $like_icon = "assets/images/Liked.png";
                    $like_class = "like liked";
                }
                $formatted_date = date_format(date_create($row['creation_date_time']), "M d, Y");

                echo("
                    <div class='card'>
                        <div class='card-header'>
                            <a href='Profile.php?username={$row['username']}'>
                                <img class='card-profile-pic' src='{$row['display_pic']}'/>
                                <p class='card-text authorname'>{$row['username']}</p>
                            </a>
                        </div>
                        <a href='./Details.php?post_id={$row['post_id']}'>
                            <div class='card-img-top'>
                                <img class='postImage' src='{$row['image']}' alt='Post Image'>
                            </div>
                        </a>
                        <div class='card-body'>
                            <img class='{$like_class}' id='{$row['post_id']}' src='{$like_icon}' height='30px' width='30px' style='float: right'>
                            <p class='text-muted likecount' style='float: right; margin-top: 2px;'>{$row['likes']}</p>
                            <a href='Profile.php?username={$row['username']}'><p class='card-text' style='font-weight: bold'>{$row['username']}</p></a>
                            <span class='caption text-muted'>{$row['caption']}</span>
                            <p class='card-text dateText'><small class='text-muted'>{$formatted_date}</small></p>
                            <a href='./Details.php?post_id={$row['post_id']}'>
                                <p class='card-text commentCount'><small class='text-muted'>{$row['comments']} comments</small></p>
                            </a>
                        </div>
                    </div>
                ");
            }
        }
        else {
            echo("reachedMax");
        }
    }
}
?>
