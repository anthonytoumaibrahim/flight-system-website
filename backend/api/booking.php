<?php
require_once("../config.php");

// Verify user
$id = verifyToken();

if (!$id) {
    exit(json_encode(['error' => 'Unauthorized user.']));
}

$data = getJSONPost();

$fullname = $data['fullname'] ?? "";
$email = $data['email'] ?? "";
$flightId = $data['flight_id'] ?? "";
// $seatId = $data['seat_id'] ?? 1;
$amount = $data['amount'] ?? 0;
$paymentMethod = $data['payment_method'] ?? "";


// $insertClientQuery = "INSERT INTO client (fullname, email) VALUES ('$fullname', '$email')";
// if ($mysqli->query($insertClientQuery)) {
//     $id = $mysqli->insert_id;
// } else {
//     echo "Error: " . $insertClientQuery . "<br>" . $mysqli->error;
// }

$bookingStatus = "Confirmed";
$insertBookingQuery = "INSERT INTO booking (booking_status, flight_id, client_id) VALUES ('$bookingStatus', $flightId, $id)";

if ($mysqli->query($insertBookingQuery)) {
    $bookingId = $mysqli->insert_id;
} else {
    echo "Error: " . $insertBookingQuery . "<br>" . $mysqli->error;
}

// $updateSeatQuery = "UPDATE seat SET status = 'Booked' WHERE seat_id = $seatId";
// if ($mysqli->query($updateSeatQuery)) {
//     echo "Seat updated successfully";
// } else {
//     echo "Error: " . $updateSeatQuery . "<br>" . $mysqli->error;
// }

$paymentStatus = "Success";
$insertPaymentQuery = "INSERT INTO payment (amount, status, method, booking_id) 
VALUES ($amount, '$paymentStatus', '$paymentMethod', $bookingId)";
$mysqli->query($insertPaymentQuery);

$mysqli->close();

echo "Booking successful!";
