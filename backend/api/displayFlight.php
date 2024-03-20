<?php
include ("config.php");

$destination = $_POST['destination'];
$departureDate = $_POST['departureDate'];
$returnDate = $_POST['returnDate'];
$numPassengers = $_POST['numPassengers'];

$sql = "SELECT * FROM flight WHERE departure_airport_id = ? AND arrival_airport_id = ? AND depart_datetime >= ? AND return_datetime <= ? AND available_seats >= ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iisis", $departureAirportId, $arrivalAirportId, $departureDate, $returnDate, $numPassengers);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "Airline: " . $row['airline_name'] . "\n";
    echo "Flight Number: " . $row['flight_number'] . "\n";
    echo "Departure Time: " . date('Y-m-d H:i:s', $row['depart_datetime']) . "\n";
    echo "Arrival Time: " . date('Y-m-d H:i:s', $row['arrival_datetime']) . "\n";
    echo "Price: $" . $row['flight_price'] . "\n\n";
}

$stmt->close();
$mysqli->close();
