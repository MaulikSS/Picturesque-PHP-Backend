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
    if(isset($_POST['getActivity']) && $_POST['getActivity']==1 && isset($_POST['limit'])){
        global $conn;
        $limit = (int)mysqli_real_escape_string($conn, $_POST['limit']);
        $sql = "SELECT * FROM user_activity ORDER BY posts_published DESC LIMIT {$limit}";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            die("QUERY FAILED - ".mysqli_error($conn));
        }
        $json = array();
        while($row = mysqli_fetch_assoc($result)){
            $json[] = $row;
        }
        $json = json_encode($json);
        exit($json);
    }
}
