<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_schedule') {
    $user_id = verifyToken();
    if (!$user_id) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit();
    }
    

    $required_fields = ['flight_id', 'depart_datetime', 'arrival_datetime'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            http_response_code(400);
            echo json_encode(["error" => "$field is required"]);
            exit();
        }
    }
    
    $flight_id = $_POST['flight_id'];
    $depart_datetime = $_POST['depart_datetime'];
    $arrival_datetime = $_POST['arrival_datetime'];
    
    $sql = "UPDATE flight SET depart_datetime = '$depart_datetime', arrival_datetime = '$arrival_datetime' WHERE flight_id = $flight_id";
    if ($mysqli->query($sql)) {
        echo json_encode(["success" => true, "message" => "Flight schedule updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update flight schedule"]);
    }
}