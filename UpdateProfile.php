<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['id'])){
    redirect('Login.php');
}

$current_user_id = $_SESSION['id'];
$current_username = $_SESSION['username'];

global $conn;
$sql1="SELECT first_name, last_name, bio, username, display_pic FROM user WHERE username='{$current_username}'";
$result1 = mysqli_query($conn, $sql1);
if(!$result1){
    die("QUERY FAILED - " . mysqli_error($conn));
}
$result1 = mysqli_fetch_assoc($result1);
$is_successful = -1;

// $is_successful = 0 -> Successful
// $is_successful = 1 -> username exists

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['submit1'])){
        if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['bio'])){
            $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
            $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
            $rawusername = trim($_POST['username']);
            $username = mysqli_real_escape_string($conn, trim($_POST['username']));
            $bio = mysqli_real_escape_string($conn, trim($_POST['bio']));
            $sql2 = "UPDATE user SET first_name='{$first_name}', last_name='{$last_name}', username='{$username}', bio='{$bio}' WHERE username='{$current_username}'";
            $sql3 = "SELECT count(*) AS count FROM user WHERE username = '{$username}'";
            $result3 = mysqli_query($conn, $sql3);
            if(!$result3){
                die("QUERY FAILED - " . mysqli_error($conn));
            }
            $result3 = mysqli_fetch_assoc($result3);
            if($result3['count']==1){
                if($username==$current_username){
                    $result2 = mysqli_query($conn, $sql2);
                    if(!$result2){
                        die("QUERY FAILED - " . mysqli_error($conn));
                    }
                    $is_successful = 0;
                    $_SESSION['username'] = $rawusername;
                    redirect('Profile.php');
                }
                else{
                    $is_successful = 1;
                }
            }
            else{
                $result2 = mysqli_query($conn, $sql2);
                if(!$result2){
                    die("QUERY FAILED - " . mysqli_error($conn));
                }
                $_SESSION['username'] = $rawusername;
                $is_successful = 0;
                redirect('Profile.php');
            }
        }
    }
    else if(isset($_POST['submit2'])){
        if(isset($_FILES['image'])){
            $target_dir = "usermedia/". $_SESSION['username'] . "/";
            if (!file_exists($target_dir)){
                mkdir($target_dir, 0777, true);
            }
            $imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION));
            $target_file = $target_dir . "ProfilePic." . $imageFileType;
            $uploadOk = 1;
            if(isset($_POST['submit2'])){
                $check = getimagesize($_FILES['image']['tmp_name']);
                if($check !== false){
                    $uploadOk = 1;
                }
                else{
                    $uploadOk = 0;
                    $errorCode = 1; //File is not an image.
                }
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $uploadOk = 0;
                $errorCode = 2; //Not valid format.
            }
            if ($uploadOk == 0) {
                //Do nothing.
            }
            else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $errorCode = 0; //Successfully Uploaded
                    $sql = "UPDATE user SET display_pic='{$target_file}' WHERE id={$current_user_id}";
                    global $conn;
                    $result = mysqli_query($conn, $sql);
                    if (!$result) {
                        $errorCode = 5; //SQL Error while uploading
                        die("QUERY FAILED - " . mysqli_error($conn));
                    }
                    redirect('Profile.php');
                }
                else {
                    $errorCode = 4; //Some error uploading the file.
                }
            }
        }
    }

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
    #profileUpdateForm{
        padding-bottom: 60px;
    }
    #preview{
        max-width: 80px !important;
        max-height: 80px !important;
        border-radius: 40px;
        margin: 0 auto;
        width: 32%;
        display: flex;
        align-items: center;
        align-content: flex-start;
        justify-content: center;
        overflow: hidden;
    }
    .error{
        margin-left: 5px;
        margin-bottom: 10px;
        color: blue !important;
    }
    </style>
    <title>Update Profile</title>
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
                <br/>
                <form action="UpdateProfile.php" method="POST" enctype="multipart/form-data" id="profilePicUpdateForm">
                    <div class="form-group">
                        <label for="Id_Photo">Profile Pic</label>
                        <input type="file" class="form-control-file" id="id_photo" name="image" required>
                    </div>
                    <div class="form-group previewImage" style="text-align: center">
                        <span style="font-size: 1.2rem;">Preview</span>
                        <br>
                        <img id="preview" style="max-width: 100%; max-height: 100%" src="<?php echo $result1['display_pic']; ?>"/>
                    </div>
                    <div class="form-group" style="text-align: center;">
                        <input type="submit" class="btn btn-success" name="submit2" value="Update Profile Pic" style="width: 100%;">
                    </div>
                </form>
                <form action="UpdateProfile.php" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                    <div class="form-group">
                        <label for="Id_First_Name">First Name</label>
                        <input type="text" class="form-control" id="id_first_name" name="first_name" aria-describedby="firstname" placeholder="First Name"
                        value="<?php if($is_successful==1){ echo $_POST['first_name']; } else { echo "{$result1['first_name']}"; } ?>" minlength="2" oninvalid="this.setCustomValidity('First name should be minimum 2 characters')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Last_Name">Last Name</label>
                        <input type="text" class="form-control" id="id_last_name" name="last_name" aria-describedby="lastname" placeholder="Last Name"
                        value="<?php if($is_successful==1){ echo $_POST['last_name']; } else { echo "{$result1['last_name']}"; } ?>" minlength="2" oninvalid="this.setCustomValidity('Last name should be minimum 2 characters')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_Username">Username</label>
                        <small class="text-muted error usernameError">&nbsp;<?php if($is_successful==1){ echo "Username is already taken."; } ?></small>
                        <input type="text" class="form-control" id="id_username" name="username" aria-describedby="username" placeholder="Username"
                        value="<?php if($is_successful==1){ echo $_POST['username']; } else { echo "{$result1['username']}"; } ?>" pattern="(([a-z|A-Z]+)|(\d*))+" minlength="3" maxlength="16" oninvalid="this.setCustomValidity('Username should be between 3 and 16 characters, and contain at least one letter')" oninput="this.setCustomValidity('')" required>
                    </div>
                    <div class="form-group">
                        <label for="Id_bio">Bio</label>
                        <textarea class="form-control" name="bio" rows="2" cols="80" form="profileUpdateForm" placeholder="Bio"><?php if($is_successful==1){ echo $_POST['bio']; } else { echo "{$result1['bio']}"; } ?></textarea>
                    </div>
                    <small class="text-muted error emailError"><?php //if($errorCode>=0) echo $errorArray[$errorCode]; ?></small>
                    <div class="form-group" style="text-align: center;">
                        <input type="submit" class="btn btn-success" name="submit1" value="Update Details" style="width: 100%;">
                    </div>
                </form>
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
    function readURL(input) {
        if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#id_photo").change(function(){
        $(this).parent().siblings('.previewImage').show();
        readURL(this);
    });
    </script>
</body>
</html>
