<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function send_email($confirm_code,$username) {
    $to = get_email($username);
    $subject = "Your verification code";
    $message = "The verification code for $username is $confirm_code";
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
        $_SESSION['verificationCode'] = '0';
        echo "User is now verified!";
        mysqli_close($conn);
        return false;
    } else if ($assoc['vCode'] === '0') {
        echo "User has already been verified";
        mysqli_close($conn);
        return true;
    } else {
        echo "Incorrect verification code entered.";
        mysqli_close($conn);
        return true;
    }
}

function user_verified($conn, $username, $verification_code) {
    $stmt = $conn->prepare("Update reg_users set vCode = 0 where Username = ? and vCode = ?");
    $stmt->bind_param('ss', $username, $verification_code);
    $stmt->execute();
}

function get_email($username){  
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from reg_users where Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
    mysqli_close($conn);
    return $assoc['Email'];
}

?>
