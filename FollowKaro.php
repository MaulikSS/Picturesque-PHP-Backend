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
    if(isset($_POST['option']) && isset($_POST['username'])){
        $username = mysqli_real_escape_string($conn,$_POST['username']);
        $option = mysqli_real_escape_string($conn,$_POST['option']);
        global $conn;
        if($option=='follow'){
            $sql = "SELECT * FROM follow WHERE follower={$current_user_id} AND followee=(SELECT id FROM user WHERE username='{$username}')";
            if(mysqli_num_rows(mysqli_query($conn, $sql))==0){
                date_default_timezone_set('Asia/Calcutta');
                $date = date('Y-m-d H:i:s');
                $sql = "INSERT INTO request(creation_date_time, sender, receiver) VALUES ('{$date}', {$current_user_id}, (SELECT id FROM user WHERE username='{$username}'))";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("QUERY FAILED - " . mysqli_error($conn));
                }
                echo true;
            }
        }
        else if($option=='unfollow'){
            $sql = "SELECT * FROM follow WHERE follower={$current_user_id} AND followee=(SELECT id FROM user WHERE username='{$username}')";
            if(mysqli_num_rows(mysqli_query($conn, $sql))!=0){
                $sql = "DELETE FROM follow WHERE follower={$current_user_id} AND followee=(SELECT id FROM user WHERE username='{$username}')";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("QUERY FAILED - " . mysqli_error($conn));
                }
                echo true;
            }
        }
        else if($option=='cancelRequest'){
            $sql = "SELECT * FROM request WHERE sender={$current_user_id} AND receiver=(SELECT id FROM user WHERE username='{$username}')";
            if(mysqli_num_rows(mysqli_query($conn, $sql))!=0){
                $sql = "DELETE FROM request WHERE sender={$current_user_id} AND receiver=(SELECT id FROM user WHERE username='{$username}')";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("QUERY FAILED - " . mysqli_error($conn));
                }
                echo true;
            }
        }
    }
}

?>
