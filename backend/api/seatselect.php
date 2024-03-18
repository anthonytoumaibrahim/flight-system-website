seat select:
<?php

include("config.php");
function getAvailableSeats($flightId) {
    global $mysqli;
    
    $sql = "SELECT seat_id, number, class FROM seat WHERE status = 'Available' AND flight_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $flightId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seats = [];
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
    return $seats;
}
function reserveSeat($flightId, $seatId) {
    global $mysqli;
    
    $checkSeatQuery = "SELECT status FROM seat WHERE seat_id = ? AND flight_id = ?";
    $checkStmt = $mysqli->prepare($checkSeatQuery);
    $checkStmt->bind_param("ii", $seatId, $flightId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows == 0) {
        return "Seat not found for this flight.";
    }
    $seat = $checkResult->fetch_assoc();
    if ($seat['status'] != 'Available') {
        return "Seat is already reserved or booked.";
    }
    

    $reserveQuery = "UPDATE seat SET status = 'Reserved' WHERE seat_id = ? AND flight_id = ?";
    $reserveStmt = $mysqli->prepare($reserveQuery);
    $reserveStmt->bind_param("ii", $seatId, $flightId);
    if ($reserveStmt->execute()) {
        return "Seat reserved successfully.";
    } else {
        return "Failed to reserve seat.";
    }
}

function releaseSeat($flightId, $seatId) {
    global $mysqli;
    
    $checkSeatQuery = "SELECT status FROM seat WHERE seat_id = ? AND flight_id = ?";
    $checkStmt = $mysqli->prepare($checkSeatQuery);
    $checkStmt->bind_param("ii", $seatId, $flightId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows == 0) {
        return "Seat not found for this flight.";
    }
    $seat = $checkResult->fetch_assoc();
    if ($seat['status'] != 'Reserved') {
        return "Seat is not reserved.";
    }
    
    $releaseQuery = "UPDATE seat SET status = 'Available' WHERE seat_id = ? AND flight_id = ?";
    $releaseStmt = $mysqli->prepare($releaseQuery);
    $releaseStmt->bind_param("ii", $seatId, $flightId);
    if ($releaseStmt->execute()) {
        return "Seat released successfully.";
    } else {
        return "Failed to release seat.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['flight_id'])) {
    $flightId = $_GET['flight_id'];
    $availableSeats = getAvailableSeats($flightId);
    header('Content-Type: application/json');
    echo json_encode($availableSeats);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flight_id']) && isset($_POST['seat_id'])) {
    $flightId = $_POST['flight_id'];
    $seatId = $_POST['seat_id'];
    $response = reserveSeat($flightId, $seatId);
    header('Content-Type: application/json');
    echo json_encode(["message" => $response]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['release_seat']) && isset($_POST['flight_id']) && isset($_POST['seat_id'])) {
    $flightId = $_POST['flight_id'];
    $seatId = $_POST['seat_id'];
    $response = releaseSeat($flightId, $seatId);
    header('Content-Type: application/json');
    echo json_encode(["message" => $response]);
}

?>