<?php
require 'mysql.php';
if (isset($_SESSION['verificationCode']) && $_SESSION['verificationCode'] !== '0') {//If the user is logged in, and if they are NOT a verified user..
    header("Location: verification.php"); //..redirect them to verification.php.
    exit;
} else if (!isset($_SESSION['user'])) {//If the user is not logged in..
    header("Location: login.php"); //..redirect them to login.php
    exit;
}
unset($_SESSION['propertyid']);
unset($_SESSION['propertySelected']);
unset($_SESSION['propertyAdded']);
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

        <link rel="stylesheet" href="ammap/ammap.css" type="text/css" media="all" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" type="text/css" href="dist/sweetalert.css"/>
        <link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-1.23.1.css"/>
        <link rel="stylesheet" href="css/input.css?v=1.07"/>
        <link rel="stylesheet" href="css/background.css?v=1.07"/>
        <link rel="stylesheet" href="css/heading.css?v=1.07"/> 
        <link rel="stylesheet" href="css/table.css?v=1.07"/> 
        <link rel="stylesheet" href="css/main_content.css?v=1.07"/>
        <link rel="stylesheet" href="css/container.css?v=1.07"/>
        <link rel="stylesheet" href="css/bootstrap.css?v=1.07"/>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <div id="background"></div>   <!-- Having two backgrounds, allows one to overlay the other to create a tint effect. -->
        <div id="backgroundLayer"></div>
        <div id="headingColour">
            <h1 id="CDT_Heading" ><a href="index.php" class="homeLink">Brighton and Hove Agency</a></h1> 
        </div>
        <a href="accommodation.php">Add new Property</a>
        <h3>View Properties</h3>
        <?php
        if (isset($_SESSION['user'])) {
            //NOTE: IN THE FUTURE, LOGIN WILL TAKE YOU TO THE BROWSE PAGE. 
            $_SESSION["propertyid"] = null; // LOGIN,VERIFICATION, IMAGE UPLOAD, ACCOMDATION ADD AND UPDATE ALL LINK BACK TO HERE AT THE MOMENT, NEED A WAY TO CLEAR PROPERTYID SESSION. 
            echo "User " . $_SESSION['user'] . " is logged in <br/>";
            echo "PropertyID: " . $_SESSION["propertyid"] . " <br/>";
//    get_propertyid($_SESSION['userid']);
            view_property($_SESSION['userid']);
        } else {
            echo "UserID: " . $_SESSION["userid"] . " <br/>";
            echo "PropertyID: " . $_SESSION["propertyid"] . " <br/>";
        }
        ?>

    </body>

</html>


