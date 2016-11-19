<?php
require 'mysql.php';
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
            <h1 id="CDT_Heading" ><a href="index.xhtml" class="homeLink">Brighton and Hove Agency</a></h1> 
        </div>
        <a href="accommodation.php">Add new Property</a>
        <h3>View Properties</h3>

        <form action="browse.php" method="post" enctype="multipart/form-data">
            <label>Search</label><input id="searchBox" type="text"  name="wordSearched" /> <br/>               
            <input type="submit" name="search" value="Search"></input><br/>
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
    </body>

</html>


