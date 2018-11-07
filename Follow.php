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
    if(isset($_POST['user_id']) && isset($_POST['decision'])){
        $user_id = (int)mysqli_real_escape_string($conn,$_POST['user_id']);
        $decision = mysqli_real_escape_string($conn,$_POST['decision']);
        if($decision == 'accept'){
            $sql = "SELECT * FROM follow WHERE follower = {$user_id} AND followee = {$current_user_id}";
            global $conn;
            $result = mysqli_query($conn, $sql);
            if(!$result){
                die("QUERY FAILED - ".mysqli_error($conn));
            }
            if(mysqli_num_rows($result)==0){
                $sql = "INSERT INTO follow(follower, followee) VALUES ({$user_id}, {$current_user_id})";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("QUERY FAILED - ".mysqli_error($conn));
                }
            }
        }
        else if($decision == 'reject'){
            $sql = "SELECT * FROM request WHERE receiver={$current_user_id} AND sender={$user_id}";
            global $conn;
            $result = mysqli_query($conn, $sql);
            if(!$result){
                die("QUERY FAILED - ".mysqli_error($conn));
            }
            if(mysqli_num_rows($result)!=0){
                $sql = "DELETE FROM request WHERE receiver={$current_user_id} AND sender={$user_id}";
                $result = mysqli_query($conn, $sql);
                if(!$result){
                    die("QUERY FAILED - ".mysqli_error($conn));
                }
            }
        }
        redirect('Requests.php');
    }
}
