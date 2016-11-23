<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function add_property() {
    $conn = sql_connection();
    $stmt = $conn->prepare("INSERT INTO Property VALUES ('DEFAULT',?,?,?,?,?)");
    $stmt->bind_param('issid', $_SESSION['userid'], $_POST['area'], $_POST['address'], $_POST['rooms'], $_POST['rate']);
    if ($stmt->execute()) {
        $_SESSION['propertyAdded'] = true;
        header("Location: image_upload.php");
        mysqli_close($conn);
        exit;
    } else {
        echo "Error: <br>" . $conn->error;
        mysqli_close($conn);
    }
}

function new_property($address) {//In the case of update, the address is from the database
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from Property where Address = ?");
    $stmt->bind_param('s', $address);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
    if ($result->num_rows > 0) {//If another property with the address already exists..     
        //Needs to be used for comparison
        if (isset($_SESSION['propertyid']) && $assoc['id'] === intval($_SESSION['propertyid'])) {//..and the address of the property being updated belongs to the user
            mysqli_close($conn);
            return true;//Allow them to update or reuse the address without any problems
        } else {//If they don't own the property, that means they cannot use the address
            echo "Address already in use.";
            mysqli_close($conn);
            return false;
        }
    } else {//If no other property with the address exists..
        echo "Address has been created!";//..allow property to be added.
        mysqli_close($conn);
        return true;
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
    $table = " <form action='mysql.php' method='post'><div class='buttons-div'><input type='submit' class='curved-button' class='curved-button' name='edit' value='Edit'/><input type='submit' class='curved-button' class='curved-button' name='delete' value='Delete'/><input type='submit' class='curved-button' class='curved-button' name='manage_images' value='Manage images'/></div><div class='table-div'><table class='tablesorter table-hover' id='carrierTable' align='center'>" . "<thead><tr><th>ID</th><th>Area</th><th>Address</th><th>Bedrooms</th><th>Weekly rate (£)</th></tr></thead>" . "<tbody>";
    while ($assoc = $result->fetch_assoc()) {
        $table.="<tr>";
        $table.= "<td>" . $assoc['id'] . "</td>" . "<td>" . $assoc['Area'] . "</td>" . "<td>" . $assoc['Address'] . "</td>" . "<td>" . $assoc['Bedrooms'] . "</td>" . "<td>" . $assoc['Rate'] . "</td>" . "<td><input type='radio' name='radio' value='" . $assoc['id'] . "'/></td>";
        $table.="</tr>";
    }
    $table.= "</tbody></table></div> </form>";
    echo "$table";
    mysqli_close($conn);
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
    mysqli_close($conn);
}

function get_property($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Select * from Property where id = ? ORDER BY `id` DESC LIMIT 1");
    $stmt->bind_param('i', $propertyid);
    $stmt->execute();
    $result = $stmt->get_result();
    $assoc = $result->fetch_assoc();
    mysqli_close($conn);
    return $assoc;
}

function update_property($propertyid) {//return array, use array for values in accom_update.
    $conn = sql_connection();
    $stmt = $conn->prepare("Update Property set Area = ?, Address = ?, Bedrooms = ?, Rate = ? where id = ?;");
    $stmt->bind_param('ssidi', $_POST['area'], $_POST['address'], $_POST['rooms'], $_POST['rate'], $propertyid);
    $stmt->execute();
    mysqli_close($conn);
}

function delete_property($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Delete from Property where id = ? and user_id = ?");
    $stmt->bind_param('ii', $propertyid, $_SESSION['userid']);
    $stmt->execute();
    mysqli_close($conn);
}

?>