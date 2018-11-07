<?php
include ('DBconnect.php');
include ('functions.php');

session_start();

if(!isset($_SESSION['username'])){
    redirect('Login.php');
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_GET['post_id'])) {
        $post_id = (int)mysqli_real_escape_string($conn, $_GET['post_id']);
        $text = mysqli_real_escape_string($conn,$_POST['comment']);
        if($text==""){

        }
        global $conn;
        date_default_timezone_set('Asia/Calcutta');
        $date = date('Y-m-d H:i:s');
        $sql="INSERT INTO comment(text, creation_date_time,user_id, post) VALUES ('{$text}', '{$date}', {$_SESSION['id']}, {$post_id})";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            die("QUERY FAILED - ".mysqli_error($conn));
        }
        else{
            redirect('Details.php?post_id='.$post_id);
        }
    }
}
else{
    redirect('Home.php');
}
?>
