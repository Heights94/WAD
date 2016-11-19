<?php

require ("PassHash.php");
require ("validation.php");
//require ("propertylist.php");


session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST["register"])) {
    if (validate_name() && validate_password() && validate_email() && validate_captcha()) { //CHECK FOR DUPLICATE EMAIL?
        if (new_username($_POST["userName"])) {
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

if (isset($_POST["submit"])) {
    if (validate_rate() && validate_property_fields($_POST["area"]) && validate_property_fields($_POST["address"])) {
        add_property();
    }
}



if (isset($_POST["update"])) {
    if (validate_rate() && validate_property_fields($_POST["area"]) && validate_property_fields($_POST["address"])) {
        update_property($_SESSION["propertyid"]);

        header("Location: propertylist.php");
    }
}

if (isset($_POST["delete"])) {
    if (isset($_POST["radio"])) {
//check what the session is equal to!
        delete_property($_POST["radio"]);
//         echo "User id is " . $_SESSION['userid'] . " Property id is " . $_SESSION['propertyid'];
        header("Location: propertylist.php");
        exit;
    }
}

if (isset($_POST["delete_image"])) {//needs validation
    if (isset($_POST["image_radio"])) {
        delete_image($_POST["image_radio"]);
        echo "Image deleted";
    }
}



if (isset($_POST["edit"])) {
    if (isset($_POST["radio"])) {
        $_SESSION["propertyid"] = $_POST["radio"];
        header("Location: accommodation_update.php");
        exit;
//    update_details();
    }
}

if (isset($_POST["manage_images"])) {
    $_SESSION["propertyid"] = $_POST["radio"];
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

function captcha() {
    if (isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {
        echo "Correct Code Entered <br/>";
        return true;
//Do you stuff
    } else {
        die("Wrong Code Entered");
        return false;
    }
}

function register_user($username, $password, $email_address) {
    $conn = sql_connection();
    $verification_code = substr(sha1(uniqid(rand())), 1, 5);
    $pass_hash = PassHash::hash($password);
    $stmt = $conn->prepare("INSERT INTO reg_users VALUES ('DEFAULT' ,?,?,?,?)");
    $stmt->bind_param('ssss', $username, $pass_hash, $email_address, $verification_code);
//    $stmt->bindParam(':pass', $pass_hash);
//    $stmt->bindParam(':email', $email_address);
//    $stmt->bindParam(':vcode', $verification_code);
//    $stmt->execute();
//        $result = $conn->query("INSERT INTO reg_users VALUES ('$username','$password','$email_address' ");//The function, select_query($i), uses the returned value to create a 'INSERT INTO SELECT' statement to copy data from UAT_CARRIER_INFO into UAT_RECORDS.
    if ($stmt->execute()) {
        send_email($verification_code);
        $_SESSION['user'] = $username;
        echo "New record created successfully";
    } else {
        echo "Error: <br>" . $conn->error;
    }
}

function login_user($username, $password) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from reg_users where Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
//    while ($assoc = $result->fetch_assoc()) {
//    var_dump($assoc);
//    }
//    $row = mysqli_fetch_assoc($result);
    if ((PassHash::check_password($assoc['Password_'], $password))) {
        $_SESSION['user'] = $username;
        $_SESSION['userid'] = $assoc['id'];
        setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 day
        if ($assoc['vCode'] !== '0') {
            header("Location: verification.php");
            exit;
        } else {

            header("Location: propertylist.php");
            exit;
        }
    } else {
        echo "Incorrect Username or Password entered.";
    }
}

function new_username($username) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from reg_users where Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username already exists!";
        return false;
    } else {
        echo "Username has been created!";
        return true;
    }
}

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

function add_property() {
    $conn = sql_connection();
    $stmt = $conn->prepare("INSERT INTO Property VALUES ('DEFAULT',?,?,?,?,?)");
    $stmt->bind_param('issid', $_SESSION['userid'], $_POST['area'], $_POST['address'], $_POST['rooms'], $_POST['rate']);
//    $stmt->bindParam(':pass', $pass_hash);
//    $stmt->bindParam(':email', $email_address);
//    $stmt->bindParam(':vcode', $verification_code);
//    $stmt->execute();
//        $result = $conn->query("INSERT INTO reg_users VALUES ('$username','$password','$email_address' ");//The function, select_query($i), uses the returned value to create a 'INSERT INTO SELECT' statement to copy data from UAT_CARRIER_INFO into UAT_RECORDS.
    if ($stmt->execute()) {

        header("Location: propertylist.php");
        exit;
//        require ("upload.php"); //Once the property has been added, upload the image to the server and mysql.
//         $_SESSION['userid'] = $assoc['id'];
//        send_email($verification_code);
//        $_SESSION['user'] = $username;
        echo "New record created successfully";
    } else {
        echo "Error: <br>" . $conn->error;
    }
}

function browse($iterator) {
//    $conn = sql_connection();
//    $stmt = count_pages($conn);
////    $stmt = $conn->prepare("Select * from Property");
////    $stmt->execute();
//    $result = $stmt->get_result();
//    $assoc = $result->fetch_assoc();//do while loop print table
// "
//    $table = " <form action='mysql.php' method='post'>";
        $table="<div><br/><div id='property_image'>";
        $images = get_image($iterator['id']);
        for ($i = 0; $i < count($images); $i++) {
//        var_dump($images[$i]['img']);
            if (isset($images[$i]['img'])) {
                $table.= "<img src='" . $images[$i]['img'] . "'/>";
            }
        }
        $table.= "</div><br/>"/*."<label>ID:</label>" . $assoc['id'] . "<br/>" */
                . "<label>Area:</label>" . $iterator['Area'] . "<br/>" 
                . "<label>Address:</label>" . $iterator['Address'] . "<br/>" 
                . "<label>Bedrooms:</label>s" . $iterator['Bedrooms'] . "<br/>"  
                . "<label>Rate:</label>" . $iterator['Rate'] . "<br/>"  ;
//                . "<td><input type='radio' name='radio' value='" . $assoc['id'] . "'/><br/>";
        
    $table.= "</div>";

return "$table";

   
}

function count_pages($like_clause){
    if(!isset($like_clause)){
        $where_clause = '';
    } else {
        $where_clause = " where Area Like '%$like_clause%'";//PRONE TO INJECTIONS STILL
    }
    $dbh = sql_connection();
    try {

    // Find out how many items are in the table
    $result = $dbh->prepare('
        SELECT
            *
        FROM
            Property'.$where_clause.'
    ');
    
    $result->execute();
    $total = $result->get_result();
//    if ($result->num_rows > 2) {

    // How many items to list per page
    $limit = 5;
//     echo $total->num_rows;
    // How many pages will there be
    $pages = ceil($total->num_rows / $limit);
    
    // What page are we currently on?
    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));

    // Calculate the offset for the query
    $offset = ($page - 1)  * $limit;

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
            Property '.$where_clause.'
        ORDER BY
            id
        LIMIT
            ?
        OFFSET
            ?
    ');

    // Bind the query params
    $stmt->bind_param('ii', $limit,$offset);
    $stmt->execute();

    $result2 = $stmt->get_result();
    // Do we have any results?
    if ($result2->num_rows > 0) {
        // Define how we want to fetch the results
       
    while ($iterator = $result2->fetch_assoc()){
//        $iterator = new IteratorIterator($stmt);
 $table = browse($iterator);//MAKE ANOTHER FILE TO ECHO RESULTS
 echo $table;
    }
        // Display the results
//        foreach ($iterator as $row) {
//            echo '<p>', $row['name'], '</p>';
//        }
//        return $stmt;
    } else {
        echo '<p>No results could be displayed.</p>';
    }

} catch (Exception $e) {
    echo '<p>', $e->getMessage(), '</p>';
}
}

function view_property($userid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from Property where user_id = ?");
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $id = 'id';
    $echo = "<?php echo 'This just happened..'; ?>";
    $result = $stmt->get_result();
//    $assoc = $result->fetch_assoc();//do while loop print table
// "
    $table = " <form action='mysql.php' method='post'><input type='submit' name='edit' value='Edit'/><input type='submit' name='delete' value='Delete'/><input type='submit' name='manage_images' value='Manage images'/><table class='tablesorter table-hover' id='carrierTable' align='center'>" . "<thead><tr><th>ID</th><th>Area</th><th>Address</th><th>Bedrooms</th><th>Weekly rate (£)</th></tr></thead>" . "<tbody>";
    while ($assoc = $result->fetch_assoc()) {
        $table.="<tr>";
        $table.= "<td>" . $assoc['id'] . "</td>" . "<td>" . $assoc['Area'] . "</td>" . "<td>" . $assoc['Address'] . "</td>" . "<td>" . $assoc['Bedrooms'] . "</td>" . "<td>" . $assoc['Rate'] . "</td>" . "<td><input type='radio' name='radio' value='" . $assoc['id'] . "'/></td>";
        $table.="</tr>";
    }
    $table.= "</tbody></table> </form>";
    echo "$table";
}

function update_details($userid, $verification_code) {//return array, use array for values in accom_update.
    $stmt = $conn->prepare("Update reg_users set Username = ?, Password_ = ?, Email = ? where id = ?;");
    $stmt->bind_param('ssi', $username, $verification_code);
    $stmt->execute();
}

function update_property($propertyid) {//return array, use array for values in accom_update.
    $conn = sql_connection();
    $stmt = $conn->prepare("Update Property set Area = ?, Address = ?, Bedrooms = ?, Rate = ? where id = ?;");
    $stmt->bind_param('ssidi', $_POST['area'], $_POST['address'], $_POST['rooms'], $_POST['rate'], $propertyid);
    $stmt->execute();
}

function add_image($image_name) {
    $conn = sql_connection();
    $stmt = $conn->prepare("INSERT INTO images VALUES ('DEFAULT',?,?,?,?)");
    $img = "./uploads/$image_name";
    $stmt->bind_param('isss', $_SESSION['propertyid'], $image_name, $_POST['alt_text'], $img);
//    $stmt->bindParam(':pass', $pass_hash);
//    $stmt->bindParam(':email', $email_address);
//    $stmt->bindParam(':vcode', $verification_code);
//    $stmt->execute();
//        $result = $conn->query("INSERT INTO reg_users VALUES ('$username','$password','$email_address' ");//The function, select_query($i), uses the returned value to create a 'INSERT INTO SELECT' statement to copy data from UAT_CARRIER_INFO into UAT_RECORDS.
    if ($stmt->execute()) {
        header("Location: propertylist.php");
        exit;
//        send_email($verification_code);
//        $_SESSION['user'] = $username;
        echo "New record created successfully";
    } else {
        echo "Error: <br>" . $conn->error;
    }
}

function check_image_count($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from images where property_id = ?");
    $stmt->bind_param('i', $propertyid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 2) {
        echo "Three images already uploaded!";
        return false;
    } else {
        echo "Image has been uploaded!";
        return true;
    }
}

/* Property id of last added property */

function get_propertyid($userid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from Property where user_id = ? ORDER BY `id` DESC LIMIT 1");
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
    $_SESSION['propertyid'] = $assoc['id'];
    echo "Propery id is  " . $_SESSION['propertyid'] . ".";
}

function get_property($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from Property where id = ? ORDER BY `id` DESC LIMIT 1");
    $stmt->bind_param('i', $propertyid);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
    return $assoc;
}

function get_image($propertyid) {
    $conn = sql_connection();
    $assoc = array();
    $stmt = $conn->prepare("Select * from images where property_id = ? ORDER BY `id` DESC");
    $stmt->bind_param('i', $propertyid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $assoc[] = $row;
    }
    return $assoc;
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
    return $assoc;
}

function delete_image($img) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Delete from images where img = ?");
    $stmt->bind_param('s', $img);
    $stmt->execute();
    unlink($img);
}

function delete_property($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Delete from Property where id = ? and user_id = ?");
    $stmt->bind_param('ii', $propertyid, $_SESSION['userid']);
    $stmt->execute();
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