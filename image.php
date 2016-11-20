<?php

function add_image($image_name) {
    $conn = sql_connection();
    $stmt = $conn->prepare("INSERT INTO images VALUES ('DEFAULT',?,?,?,?)");
    $img = "./uploads/$image_name";
    $stmt->bind_param('isss', $_SESSION['propertyid'], $image_name, $_POST['alt_text'], $img);
    if ($stmt->execute()) {
        header("Location: image_upload.php");
        exit;
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

function delete_image($img) {
    $conn = sql_connection();
    $stmt = $conn->prepare("Delete from images where img = ?");
    $stmt->bind_param('s', $img);
    $stmt->execute();
    unlink($img);
}

?>