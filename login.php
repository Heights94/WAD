<?php
//require 'captcha.php';
session_start();
echo "Welcome " . $_SESSION['user'];

if (isset($_SESSION['user'])) {//If already logged in, redirect to index.php.
    header("Location: index.php");
    exit;
}
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"/>
        <!--<link rel="stylesheet" type="text/css" href="dist/sweetalert.css"/>-->
        <link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-1.23.1.css"/>
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
        <a href="register.php">Register</a>
        <h3>Login details</h3>
        <div id="register">
            <form action="mysql.php" name='myForm'  onsubmit="return validateLogin()" method="post" enctype="multipart/form-data">
                <label>Username</label><input id="userName" type="text"  name="userName" onfocus="validateLogin(true)" onblur="validateLogin(false)"/> <br/>
                <label>Password</label><input id="password" type="password" name="password" onfocus="validateLogin(true)" onblur="validateLogin(false)"/> <br/>
                 <p id="validate"></p>
                <input type="submit" name="login" value="Login"></input><br/> 
            </form>
        </div>

        <script>
            $('#userName').val(getCookie('username'));
            function validateForm() {
                var x = $('#userName').val();
                if (x === "") {
                    alert("YOOO");
                    return false;
                }
            }
        </script>

    </body>

</html>


