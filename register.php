<?php
session_start();
if (isset($_SESSION['user'])) {//If already logged in, redirect to index.php.
    header("Location: index.php");
    exit;
}

// echo substr(sha1(uniqid(rand())), 1,5);  
?>

<?xml version="1.0" encoding="UTF-8"?>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Brighton and & Hove Agency</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
        <script src="./js/cookies.js?v=1.08" type="text/javascript"></script>      
        <script src="./js/validation.js?v=1.08" type="text/javascript"></script>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"/>
        <!--<link rel="stylesheet" type="text/css" href="dist/sweetalert.css"/>-->
        <link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-1.23.1.css"/>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"/>
        
        <link rel="stylesheet" href="./css/style.css"/>
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/> -->
        <meta charset="UTF-8"/>
    </head>
    <body>
        <div id="background"></div>   <!-- Having two backgrounds, allows one to overlay the other to create a tint effect. -->
        <div id="backgroundLayer"></div>
        <div id="headingColour">
            <h1 id="CDT_Heading" ><a href="index.php" class="homeLink">Brighton and Hove Agency</a></h1> 
        </div>
        <a href="login.php">Sign in</a>
        <h3>Account registration</h3>
        <div id="register">
            <form action="mysql.php" name="myForm" onsubmit="return validateRegister()" method="post" enctype="multipart/form-data">
                <label>Username</label><input type="text" id="userName" name="userName" onfocus="validateRegister()" onblur="validateRegister()"/> <br/>
                <label>Password</label><input type="password" id="password" name="password" onfocus="validateRegister()" onblur="validateRegister()"/> <br/>
                <label>Email address</label><input type="text"  id="email" name="email" onfocus="validateRegister()" onblur="validateRegister()"/><br/>
                <label>Enter Image Text</label><input id="captcha" name="captcha" type="text" maxlength="9" onfocus="validateRegister()" onblur="validateRegister()"></input>
                <img src="captchaL.php" /><br/>
                <p id="validate"></p>
                <input type="submit" name="register" value="Register"></input><br/>
                <!--<img src="captcha.php" />-->
            </form>
        </div>




    </body>

</html>


