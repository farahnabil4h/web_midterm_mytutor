<?php

if (isset($_POST['submit'])) {

    include_once("dbconnect.php");
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];
    $userPass = sha1($_POST['password']);
    $userPhoneno = $_POST['phoneno'];
    $userAddress = $_POST['address'];
    $status = "available";
    $sqlinsert = "INSERT INTO `tbl_users`(`user_name`, `user_email`,`user_pass`, 
    `user_phoneno`, `user_address`) VALUES 
    ('$userName','$userEmail','$userPass','$userPhoneno','$userAddress')";
    try{
        $conn->exec($sqlinsert);
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            $last_id = $conn->lastInsertId();
            uploadImage($last_id);
            echo "<script>alert('Registration Success')</script>";
            echo "<script>window.location.replace('login.php')</script>";
        }
    }catch(PDOException $e){
        echo "<script>alert('Registration Failed')</script>";
        echo "<script>window.location.replace('register.php')</script>";
    }

}

function uploadImage($filename)
{
    $target_dir = "../res/images/users/";
    $target_file = $target_dir . $filename . ".png";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://kit.fontawesome.com/64d58efce2.js">
    <script src="../js/script.js"></script>
     <link rel="stylesheet" href="../css/style.css" > 
    <title>Register New Account</title>
</head>

<body>
    <header>
        <div class="w3-header w3-display-container w3-green w3-padding-32 w3-center">
            <h1 style="font-size:calc(8px+4vw);">MY Tutor</h1>
            <p style="font-size:calc(8px+1vw);">Account Registration</p>
        </div>
    </header>
    <div class="w3-content w3-padding-32">
        <form class="w3-card w3-padding" action="register.php" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')">
            <div class="w3-container w3-green">
                <h3 class="w3-center">Profile</h3>
            </div>
            <div class="w3-container w3-center">
                <img class="w3-image w3-margin" src="../res/camera.png" style="height:100%;width:400px"><br>
                <input type="file" name="fileToUpload" onchange="previewFile()">
            </div>
            <hr>

            <div class="w3-row">
                <div class="w3-half" style="padding-right:4px">
                    <p>
                        <label><b>Email</b></label>
                        <input class="w3-input w3-border w3-round" name="userEmail" type="email" required>
                    </p>
                </div>

                <div class="w3-half" style="padding-right:4px">
                    <p>
                        <label><b>Password</b></label>
                        <input class="w3-input w3-border w3-round" name="password" type="password" required>
                    </p>
                </div>
            </div>

            <div class="w3-row">
                <div class="w3-half" style="padding-right:4px">
                    <p>
                        <label><b>Full Name</b></label>
                        <input class="w3-input w3-border w3-round" name="userName" type="text" required>
                    </p>
                </div>

                <div class="w3-half" style="padding-right:4px">
                    <p>
                        <label><b>Phone Number</b></label>
                        <input class="w3-input w3-border w3-round" name="phoneno" type="text" required>
                    </p>
                </div>
            </div>
            <p>
                <label><b>Address</b></label>
                <textarea class="w3-input w3-border w3-round" rows="4" width="100%" name="address" required></textarea>
            </p>
            <br>
            <p>
                <input class="w3-button w3-green w3-round w3-block w3-border" type="submit" name="submit" value="Register">
            </p>
        </form>
    </div>
    <br><br>
    <footer class="w3-footer w3-center w3-green w3-bottom">
        <p>Copyright &copy; 2022 MY Tutor</p>
    </footer>
    

</body>

</html>