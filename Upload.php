<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['username'])){
    redirect('Login.php');
}

$has_script_run = 0;
$errorCode = -1;
$errorArray = array("Image Uploaded Successfully!", "Uploaded file is not an image.", "File format invalid.", "Sorry, your file can not be uploaded.", "There was an unknown error, please try again.");

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_FILES['image']) && isset($_POST['caption'])){
        $sql = "SELECT MAX(id) as max_id FROM post";
        global $conn;
        $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $has_script_run = 1;
        $target_dir = "usermedia/". $_SESSION['username'] . "/";
        if (!file_exists($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $filename = basename($_FILES["image"]["name"]);
        //$target_file = $target_dir . ($result['max_id']+1) . " - " . substr($filename, min(strlen($filename), 30));
        $target_file = $target_dir . ($result['max_id']+1) . " - " . $filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(isset($_POST['submit'])){
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
                date_default_timezone_set('Asia/Calcutta');
                $date = date('Y-m-d H:i:s');
                $added = add_post($target_file, mysqli_real_escape_string($conn,$_POST['caption']), $date, $_SESSION['id']);
                if(!$added){
                    $errorCode = 5; //SQL Error while uploading
                }
                //sleep(2);
                redirect('Home.php');
            }
            else {
                $errorCode = 4; //Some error uploading the file.
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
    .error{
        margin-left: 5px;
        margin-bottom: 10px;
        color: blue !important;
    }
    .previewImage{
        padding-bottom: 63px;
    }
    </style>
    <title>Upload</title>
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
                <form action="Upload.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="Id_Photo">Upload Photo</label>
                        <input type="file" class="form-control-file" id="id_photo" name="image" required <?php if($errorCode>=0 && isset($filename)){ echo "value='".$filename."'";} ?>>
                    </div>
                    <div class="form-group">
                        <label for="Id_Caption">Caption</label>
                        <input type="text" class="form-control" id="id_caption" name="caption" placeholder="Caption" <?php if($errorCode>=0){ echo "value='".mysqli_real_escape_string($conn,$_POST['caption'])."'";} ?>>
                    </div>
                    <small class="text-muted error emailError"><?php if($errorCode>=0) echo $errorArray[$errorCode]; ?></small>
                    <div class="form-group" style="text-align: center;">
                        <input type="submit" class="btn btn-success" name="submit" value="Upload" style="width: 100%;">
                    </div>
                    <div class="form-group previewImage" style="display: none; text-align: center">
                        <span style="font-size: 1.2rem;">Preview</span>
                        <br>
                        <img id="preview" style="max-width: 100%; max-height: 100%"/>
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
