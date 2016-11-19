<?php

function validate_name() {
    $name = $_POST["userName"];
    if (!preg_match("/^[a-zA-Z0-9]*$/", $name) || $name === '') {
        echo "Only letters/numbers and white space allowed";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_password() {
    $password = $_POST["password"];
    if (!preg_match("/^[a-zA-Z0-9\!]*$/", $password) || $password === '') {
        echo "Only letters, numbers and ! is allowed";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_email() {
    $email = $_POST["email"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        return false;
    } else {
        return true;
    }
}

function validate_captcha() {
    if ($_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {
        echo "Correct captcha code entered";
        return true;
    } else {
        echo "Incorrect captcha code entered";
        return false;
    }
}

function validate_property_fields($var) {
    if (!preg_match("/^[a-zA-Z0-9 \'-]*$/", $var) || $var === '') {
        echo "Only letters, numbers and -' is allowed";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_rate(){
    if (!preg_match("/^[0-9]*$/", $_POST["rate"]) || $_POST["rate"] === '') {
        echo "Only numbers are allowed";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_alt() {
    $image_description = $_POST["alt_text"];
    if (!preg_match("/^[a-zA-Z0-9\!]*$/", $image_description) || $image_description === '') {
        echo "Only letters, numbers and ! is allowed";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}