<?php

require 'mysql.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function send_email($confirm_code) {
    $to = 'mohammed_omar94@hotmail.co.uk';
    $subject = "Your verification code";
    $message = "The verification code for your account is $confirm_code";
    $headers = "from: Brighton & Hove Agency <haitsu1994@gmail.com>";

    if (mail($to, $subject, $message, $headers)) {
        echo 'Email sent successfully!';
    } else {
        die('Failure: Email was not sent!');
    }
}

function verify_code($username, $verification_code) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from reg_users where Username = ? and vCode = ?");
    $stmt->bind_param('ss', $username, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
    if ($assoc['vCode'] === $verification_code) {
        user_verified($conn, $username, $verification_code);
        echo "User is now verified!";
        return false;
    } else if ($assoc['vCode'] === '0') {
        echo "User has already been verified";
        return true;
    } else {
        echo $_SESSION['user'];
        return true;
    }
}

function user_verified($conn, $username, $verification_code) {
    $stmt = $conn->prepare("Update reg_users set vCode = 0 where Username = ? and vCode = ?");
    $stmt->bind_param('ss', $username, $verification_code);
    $stmt->execute();
}

?>
