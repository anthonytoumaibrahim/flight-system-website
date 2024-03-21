<?php

include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT SUM(flight.flight_price) AS total_revenue FROM flight JOIN booking ON booking.booking_status = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        $booking_status = 'Confirmed';
        

        $stmt->bind_param("s", $booking_status);
        

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            echo json_encode(["total_revenue" => $row["total_revenue"]]);
            exit();
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to execute query"]);
            exit();
        }
        
        $stmt->close();

    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement"]);
        exit();
    }
}

http_response_code(404);
echo json_encode(["message" => "Endpoint not found"]);
exit();