<?php
require_once("../config.php");
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT SUM(flight.flight_price) AS total_revenue FROM flight JOIN booking ON booking.booking_status = 'Confirmed'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    echo json_encode(["total_revenue" => $row["total_revenue"]]);
    exit();
}


http_response_code(404);
echo json_encode(["message" => "Endpoint not found"]);
exit();
