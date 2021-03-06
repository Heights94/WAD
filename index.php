<?php
require 'mysql.php';
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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
        <script src="./js/cookies.js?v=1.08" type="text/javascript"></script>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"/>
        <!--<link rel="stylesheet" type="text/css" href="dist/sweetalert.css"/>-->
        <link rel="stylesheet" href="https://code.jquery.com/qunit/qunit-1.23.1.css"/>
        <link rel="stylesheet" href="./css/style.css"/>
        <link rel="stylesheet" href="./css/input.css"/>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <div class="container">
            <div id="background"></div>   <!-- Having two backgrounds, allows one to overlay the other to create a tint effect. -->
            <div id="backgroundLayer"></div>
            <div id="headingColour">
                <h1 id="CDT_Heading" ><a href="index.php" class="homeLink">Brighton and Hove Agency</a></h1> 
            </div>
            <div class='links-div'><form action='mysql.php' method='post' enctype='multipart/form-data'>
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo "<input class='links logout' type='submit' name='logout' value='Logout'/>";
                    } else {
                        echo "<input class='links logout' type='submit' name='goToLogin' value='Login'/>"; 
                    }
                    ?>
                </form>
                <a class='links' href='propertylist.php'>Manage Properties</a>
            </div>
            <h3>View Properties</h3>

            <form action="index.php" method="post" enctype="multipart/form-data">
                <label>Search</label><input id="searchBox" class="input-fields" type="text"  name="wordSearched" />               
                <input type="submit" class="curved-button" name="search" value="Search"></input><br/>
            </form>
            <?php
            if (isset($_POST["search"])) {
                setcookie('searchCookie', $_POST["wordSearched"], time() + (86400 * 30), "/"); // 86400 = 1 day
                count_pages($_POST["wordSearched"]); //Where it's called
            } else {
                count_pages(null);
            }
            ?>

            <script>
                $('#searchBox').val(getCookie('searchCookie'));
            </script>
        </div>
    </body>

</html>


