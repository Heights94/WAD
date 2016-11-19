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
        header("Location: propertylist.php");
        exit;
        echo "New record created successfully";
    } else {
        echo "Error: <br>" . $conn->error;
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
    $table = " <form action='mysql.php' method='post'><input type='submit' name='edit' value='Edit'/><input type='submit' name='delete' value='Delete'/><input type='submit' name='manage_images' value='Manage images'/><table class='tablesorter table-hover' id='carrierTable' align='center'>" . "<thead><tr><th>ID</th><th>Area</th><th>Address</th><th>Bedrooms</th><th>Weekly rate (£)</th></tr></thead>" . "<tbody>";
    while ($assoc = $result->fetch_assoc()) {
        $table.="<tr>";
        $table.= "<td>" . $assoc['id'] . "</td>" . "<td>" . $assoc['Area'] . "</td>" . "<td>" . $assoc['Address'] . "</td>" . "<td>" . $assoc['Bedrooms'] . "</td>" . "<td>" . $assoc['Rate'] . "</td>" . "<td><input type='radio' name='radio' value='" . $assoc['id'] . "'/></td>";
        $table.="</tr>";
    }
    $table.= "</tbody></table> </form>";
    echo "$table";
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

function update_property($propertyid) {//return array, use array for values in accom_update.
    $conn = sql_connection();
    $stmt = $conn->prepare("Update Property set Area = ?, Address = ?, Bedrooms = ?, Rate = ? where id = ?;");
    $stmt->bind_param('ssidi', $_POST['area'], $_POST['address'], $_POST['rooms'], $_POST['rate'], $propertyid);
    $stmt->execute();
}

function delete_property($propertyid) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Delete from Property where id = ? and user_id = ?");
    $stmt->bind_param('ii', $propertyid, $_SESSION['userid']);
    $stmt->execute();
}

?>