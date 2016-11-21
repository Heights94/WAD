<?php

require ("account.php");
require ("email.php");
require ("image.php");
require ("property.php");
require ("PassHash.php");
require ("validation.php");


session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST["register"])) {
    if (validate_name() && validate_password() && validate_email() && validate_captcha()) { //CHECK FOR DUPLICATE EMAIL?
        if (new_username($_POST["userName"]) && new_email($_POST["email"])) {
            register_user($_POST["userName"], $_POST["password"], $_POST["email"]);
        }
    } else {
        
    }
}

if (isset($_POST["verify"])) {
    verify_code($_SESSION['user'], $_POST["vcode"]);
}

if (isset($_POST["login"])) {
    login_user($_POST["userName"], $_POST["password"]);
}

if (isset($_POST["logout"])) {
    logout_user();
}

if (isset($_POST["submit"])) {
    if (validate_rate() && validate_property_fields($_POST["area"]) && validate_property_fields($_POST["address"]) && new_property($_POST["address"])) {
        add_property();
    }
}



if (isset($_POST["update"])) {
    if (validate_rate() && validate_property_fields($_POST["area"]) && validate_property_fields($_POST["address"]) && new_property($_POST["address"])) {
            update_property($_SESSION["propertyid"]);
            header("Location: propertylist.php");
    }
}

if (isset($_POST["delete"])) {
    if (isset($_POST["radio"])) {
//check what the session is equal to!
        delete_property_images(get_image($_POST["radio"])); //Deletes all images associated to a property first.
        delete_property($_POST["radio"]);
//         echo "User id is " . $_SESSION['userid'] . " Property id is " . $_SESSION['propertyid'];
        header("Location: propertylist.php");
        exit;
    } else {
        echo "Please select a Property first";
    }
}

if (isset($_POST["delete_image"])) {//needs validation
    if (isset($_POST["image_radio"])) {
        delete_image($_POST["image_radio"]);
        echo "Image deleted";
    } else {
        echo "No image has been selected";
    }
}

if (isset($_POST["edit"])) {//If a property was selected when clicking edit..
    if (isset($_POST["radio"])) {
        $_SESSION["propertyid"] = $_POST["radio"];
        header("Location: accommodation_update.php");
        exit;
//    update_details();
    } else {
        echo "Please select a Property first";
    }
}

if (isset($_POST["manage_images"])) {
    $_SESSION["propertyid"] = $_POST["radio"];
    $_SESSION["propertySelected"] = true;
    header("Location: image_upload.php");
    exit;
}

function sql_connection() {
    $conn = mysqli_connect("localhost:3306", "root", "", "mdb_om342");
//            mysqli_connect("mysql.cms.gre.ac.uk", "om342", "om342", "mdb_om342");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function browse($iterator) {
    $table = "<div><br/><div id='property_image'>";
    $images = get_image($iterator['id']);
    for ($i = 0; $i < count($images); $i++) {
//        var_dump($images[$i]['img']);
        if (isset($images[$i]['img'])) {
            $table.= "<img src='" . $images[$i]['img'] . "' width=30% height=30%/>";
        }
    }
    $table.= "</div><br/>"
            . "<label>Area:</label>" . $iterator['Area'] . "<br/>"
            . "<label>Address:</label>" . $iterator['Address'] . "<br/>"
            . "<label>Bedrooms:</label>s" . $iterator['Bedrooms'] . "<br/>"
            . "<label>Rate:</label>" . $iterator['Rate'] . "<br/>";

    $table.= "</div>";

    return "$table";
}

function count_pages($like_clause) {
    if (!isset($like_clause)) {
        $where_clause = '';
    } else {
        $where_clause = " where Area Like '%$like_clause%'"; //PRONE TO INJECTIONS STILL
    }
    $dbh = sql_connection();
    try {

        // Find out how many items are in the table
        $result = $dbh->prepare('
        SELECT
            *
        FROM
            Property' . $where_clause . '
    ');

        $result->execute();
        $total = $result->get_result();
        $limit = 5;

        // How many pages will there be
        $pages = ceil($total->num_rows / $limit);

        // What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default' => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = ($page - 1) * $limit;

        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total->num_rows);

        // The "back" link
        $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

        // The "forward" link
        $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

        // Display the paging information
        echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total->num_rows, ' results ', $nextlink, ' </p></div>';

        // Prepare the paged query
        $stmt = $dbh->prepare('
        SELECT
            *
        FROM
            Property ' . $where_clause . '
        ORDER BY
            id
        LIMIT
            ?
        OFFSET
            ?
    ');

        // Bind the query params
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();

        $result2 = $stmt->get_result();
        // Do we have any results?
        if ($result2->num_rows > 0) {
            // Define how we want to fetch the results

            while ($iterator = $result2->fetch_assoc()) {
                $table = browse($iterator); //MAKE ANOTHER FILE TO ECHO RESULTS
                echo $table;
            }
            mysqli_close($dbh);
        } else {
            echo '<p>No results could be displayed.</p>';
            mysqli_close($dbh);
        }
    } catch (Exception $e) {
        echo '<p>', $e->getMessage(), '</p>';
    }
}

function all_image($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from images where property_id = ? ORDER BY `id` DESC");
    $stmt->bind_param('i', $propertyid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $assoc[] = $row;
    }
    mysqli_close($conn);
    return $assoc;
}

//
//$stmt = $dbConnection->prepare('SELECT * FROM employees WHERE name = ?');
//$stmt->bind_param('s', $name);
//
//$stmt->execute();
//
//$result = $stmt->get_result();
//while ($row = $result->fetch_assoc()) {
//    // do something with $row
//}