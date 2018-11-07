<?php
include ('DBconnect.php');
include ('functions.php');

session_start();

if(!isset($_SESSION['username'])){
    redirect('Login.php');
}

if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['post_id']) && !(isset($_GET['comment_id']))){
        $post_id = (int)mysqli_real_escape_string($conn, $_GET['post_id']);
        $sql = "SELECT id FROM post_like WHERE post={$post_id} AND user={$_SESSION['id']}";
        global $conn;
        $result = mysqli_query($conn, $sql);
        if(!$result){
            die("QUERY FAILED - " . mysqli_error($conn));
        }
        if(mysqli_num_rows($result)==0){
            $sql = "INSERT INTO post_like(user, post) VALUES({$_SESSION['id']}, {$post_id})";
            $result = mysqli_query($conn, $sql);
        }
        else{
            $sql = "DELETE FROM post_like WHERE post={$post_id} AND user={$_SESSION['id']}";
            $result = mysqli_query($conn, $sql);
        }
        if(!$result){
            die("QUERY FAILED - " . mysqli_error($conn));
        }
    }
    else if(isset($_GET['comment_id']) && !(isset($_GET['post_id']))){
        $comment_id = (int)mysqli_real_escape_string($conn,$_GET['comment_id']);
        $sql = "SELECT id FROM comment_like WHERE comment={$comment_id} AND user={$_SESSION['id']}";
        global $conn;
        $result = mysqli_query($conn, $sql);
        if(!$result){
            die("QUERY FAILED - " . mysqli_error($conn));
        }
        if(mysqli_num_rows($result)==0){
            $sql = "INSERT INTO comment_like(user, comment) VALUES({$_SESSION['id']}, {$comment_id})";
            $result = mysqli_query($conn, $sql);
        }
        else{
            $sql = "DELETE FROM comment_like WHERE comment={$comment_id} AND user={$_SESSION['id']}";
            $result = mysqli_query($conn, $sql);
        }
        if(!$result){
            die("QUERY FAILED - " . mysqli_error($conn));
        }
    }
}

?>
