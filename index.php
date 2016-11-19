<?php 
session_start();
if (isset($_SESSION['user'])) {//If already logged in, redirect to browse.php.
    header("Location: browse.php");
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
        <script src="./js/connection_functions.js?v=1.08" type="text/javascript"></script> <!-- Be sure to change the version number (v=1.XX) each time you update a file. There may be problems as the browser may cache and use the older file.  -->
        <script src="./js/gui_functions.js?v=1.07" type="text/javascript"></script> <!-- Be sure to change the version number (v=1.XX) each time you update a file. There may be problems as the browser may cache and use the older file.  -->
        <script src="./js/validation_functions.js?v=1.07" type="text/javascript"></script><!-- Be sure to change the version number (v=1.XX) each time you update a file. There may be problems as the browser may cache and use the older file.  -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
        <script src="./js/jquery.tablesorter.js" type="text/javascript" ></script>
        <script src="./js//jquery.tablesorter.pager.js" type="text/javascript"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>       
        <script src="js/moment.js" type="text/javascript"></script>     
        <script src="ammap/ammap.js" type="text/javascript"></script>
        <script src="ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="./ammap/themes/light.js" type="text/javascript"></script>
        <script src="./js/map_functions.js?v=1.07" type="text/javascript"></script><!-- Be sure to change the version number (v=1.XX) each time you update a file. There may be problems as the browser may cache and use the older file.  --> 
        <script src="dist/sweetalert.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/js/bootstrap-dialog.min.js" type="text/javascript"></script>
        <!--<script src="https://code.jquery.com/qunit/qunit-1.23.1.js"></script>-->

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
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/> -->
        <meta charset="UTF-8"/>
    </head>
    <body>
        <div id="background"></div>   <!-- Having two backgrounds, allows one to overlay the other to create a tint effect. -->
        <div id="backgroundLayer"></div>
        <div id="headingColour">
            <h1 id="CDT_Heading" ><a href="index.xhtml" class="homeLink">Brighton and Hove Agency</a></h1> 
        </div>
        <a href="login.php">Sign in</a>
        <h3>Account registration</h3>
        <div id="register">
            <form action="mysql.php" method="post" enctype="multipart/form-data">
                <label>Username</label><input id="carrier_search_input" type="text"  name="userName" oninput="data_input(this)"/> <br/>
                <label>Password</label><input id="carrier_search_input" type="password" name="password" oninput="data_input(this)"/> <br/>
                <label>Email address</label><input id="carrier_search_input" type="text"  name="email" oninput="data_input(this)"/><br/>
                <label>Enter Image Text</label><input name="captcha" type="text" maxlength="9"></input>
                <img src="captchaL.php" /><br/>
                <input type="submit" name="register" value="Register"></input><br/>
                <!--<img src="captcha.php" />-->
            </form>
        </div>
        
        


    </body>

</html>


