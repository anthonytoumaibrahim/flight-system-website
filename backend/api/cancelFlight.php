<?php

require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['action']) && $_POST['action'] === 'cancel_flight') {
    $user_id = verifyToken();
    if (!$user_id) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit();
    }

    if (!isset ($_POST['flight_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Flight ID is required"]);
        exit();
    }

    $flight_id = $_POST['flight_id'];

    $sql = "UPDATE flight SET flight_status = ? WHERE flight_id = ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $flight_status, $flight_id);

        $flight_status = 'canceled';

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Flight canceled successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to cancel flight"]);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement"]);
    }
}