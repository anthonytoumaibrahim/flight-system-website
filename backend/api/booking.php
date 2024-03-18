<?php

include("config.php");

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$flightId = $_POST['flight_id'];
$seatId = $_POST['seat_id'];
$amount = $_POST['amount'];
$paymentMethod = $_POST['payment_method'];


$insertClientQuery = "INSERT INTO client (fullname, email) VALUES ('$fullname', '$email')";
if ($mysqli->query($insertClientQuery)) {
    $clientId = $mysqli->insert_id;
} else {
    echo "Error: " . $insertClientQuery . "<br>" . $mysqli->error;
}

$bookingStatus = "Confirmed";
$insertBookingQuery = "INSERT INTO booking (booking_status, seat_id, flight_id, client_id) VALUES ('$bookingStatus', $seatId, $flightId, $clientId)";

if ($mysqli->query($insertBookingQuery)) {
    $bookingId = $mysqli->insert_id;
} else {
    echo "Error: " . $insertBookingQuery . "<br>" . $mysqli->error;
}

$updateSeatQuery = "UPDATE seat SET status = 'Booked' WHERE seat_id = $seatId";
if ($mysqli->query($updateSeatQuery)) {
    echo "Seat updated successfully";
} else {
    echo "Error: " . $updateSeatQuery . "<br>" . $mysqli->error;
}

$paymentStatus = "Success";
$insertPaymentQuery = "INSERT INTO payment (amount, status, method, booking_id) 
VALUES ($amount, '$paymentStatus', '$paymentMethod', $bookingId)";
if ($mysqli->query($insertPaymentQuery)) {
    echo "Payment processed successfully";
} else {
    echo "Error: " . $insertPaymentQuery . "<br>" . $mysqli->error;
}

$mysqli->close();

echo "Booking successful!";




