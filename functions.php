<?php
include ("DBconnect.php");

function redirect($url, $permanent = false) {
	if($permanent) {
		header('HTTP/1.1 301 Moved Permanently');
	}
	header('Location: '.$url);
	exit();
}

function login_user($username, $password){
    global $conn;
    $sql = "SELECT * FROM user WHERE username='{$username}'";
    $result = mysqli_query($conn, $sql);

    if(!$result){
        die("QUERY FAILED - ".mysqli_error($conn));
    }
    else if(mysqli_num_rows($result)==0){
        return 2;  //User does not exist.
    }
    else{
        $data = mysqli_fetch_assoc($result);
        if(password_verify($password, $data['Password'])){
            $query = "SELECT id FROM user WHERE username = '{$username}'";
            $current_user_id = mysqli_query($conn, $query);
            $data = mysqli_fetch_assoc($current_user_id);
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $data['id'];
            return 0; //Successful Login.
        }
        else{
            return 1; //Wrong Password.
        }
    }
}


function register_user($first_name, $last_name, $username, $email, $password, $date){
    global $conn;
    $q1 = "SELECT * FROM user WHERE username='{$username}'";
    $r1 = mysqli_query($conn, $q1);
    if(!$r1){
        die("QUERY FAILED - ".mysqli_error($conn));
    }
    else if(mysqli_num_rows($r1)>0){
        return 1; //Username already taken.
    }

    $q2 = "SELECT * FROM user WHERE email='{$email}'";
    $r2 = mysqli_query($conn, $q2);
    if(!$r2){
        die("QUERY FAILED - ".mysqli_error($conn));
    } else if(mysqli_num_rows($r2)>0){
        return 2; //Email already in use.
    }

    $sql = "INSERT INTO user(first_name, last_name, username, email, password, acc_creation_date) VALUES ('{$first_name}', '{$last_name}', '{$username}', '{$email}', '{$password}', '{$date}')";
    $result = mysqli_query($conn, $sql);
	if(!$result){
        die("QUERY FAILED - ".mysqli_error($conn));
    }
	// $sql = "INSERT INTO follow(follower, followee) VALUES ((SELECT id FROM user WHERE username = '{$username}'), (SELECT id FROM user WHERE username = '{$username}'))";
	// $result = mysqli_query($conn, $sql);
    // if(!$result){
    //     die("QUERY FAILED - ".mysqli_error($conn));
    // }
    else {
        return 0;
    }
}


function add_post($image, $caption, $date, $user_id){
	global $conn;
	$sql = "INSERT INTO post(image, caption, creation_date_time, user_id) VALUES ('{$image}', '{$caption}', '{$date}', '{$user_id}')";
	$result = mysqli_query($conn, $sql);
	if(!$result){
        die("QUERY FAILED - ".mysqli_error($conn));
    }
	else{
		return 1;
	}
}
?>
