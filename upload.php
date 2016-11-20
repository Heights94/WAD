<?php

require ("mysql.php");


$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

if (isset($_POST["upload"])) {
    if ($_FILES["fileToUpload"]["name"] === '') {//Upload without an image
        echo "Please upload an image.<br/>";
        exit;
// Check if image file is a actual image or fake image, an image file is not null, and an alt description has been added
    } else if ($_FILES["fileToUpload"]["name"] !== '') {//NEED VALIDATION FOR ALT DESCRIPTION
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".<br/>";
            $uploadOk = 1;
        } else {
            echo "Please upload an appropiate image type.<br/>";
            exit;
        }
    }
}

if (validate_alt()) {//If the description is not set
    $uploadOk = 1;
} else {
    $uploadOk = 0;
    exit;
}

if (!check_image_count($_SESSION['propertyid'])) {//Makes sure that only three images are only uploaded. 
    echo "Sorry, only three images can be uploaded.<br/>";
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br/>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 1600000) {
    echo "Sorry, your file is too large.<br/>";
    $uploadOk = 0;
}
// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br/>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded."; //Adds image to the server

        add_image(basename($_FILES["fileToUpload"]["name"])); //Adds it to the database
    } else {
        echo "Sorry, there was an error uploading your file.<br/>";
    }
}
?>
