<?php

function validate_name() {
    $name = $_POST["userName"];
    if (!preg_match("/^[a-zA-Z0-9]*$/", $name) || $name === '') {
        echo "Only usernames with letters/numbers and white space are allowed<br/>";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_password() {
    $password = $_POST["password"];
    if (!preg_match("/^[a-zA-Z0-9\!]*$/", $password) || $password === '') {
        echo "Only passwords with letters, numbers and ! are allowed<br/>";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_email() {
    $email = $_POST["email"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format<br/>";
        return false;
    } else {
        return true;
    }
}

function validate_captcha() {
    if ($_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {
        echo "Correct captcha code entered<br/>";
        return true;
    } else {
        echo "Incorrect captcha code entered<br/>";
        return false;
    }
}

function validate_property_fields($var) {
    if (!preg_match("/^[a-zA-Z0-9 \'-]*$/", $var) || $var === '') {
        echo "Only letters, numbers, - and ' are allowed";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_rate(){
    if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $_POST["rate"]) || $_POST["rate"] === '') {
        echo "Only numbers between 0.00 to 9999.99 are allowed for the Weekly rate";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}

function validate_alt() {
    $image_description = $_POST["alt_text"];
    if (!preg_match("/^[a-zA-Z0-9\!. ]*$/", $image_description) || $image_description === '') {
        echo "Please add a appropiate description<br/>";
        return false;
    } else {
//        echo "Good job!";
        return true;
    }
}