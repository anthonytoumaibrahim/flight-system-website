<?php

$search_criteria = getJSONPost();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestData = getJSONPost();

    $destination = $requestData['destination'];
    $departureDate = $requestData['departureDate'];
    $departureTime = $requestData['departureTime'];
    $arrivalDate = $requestData['arrivalDate'];
    $arrivalTime = $requestData['arrivalTime'];
    $numPassengers = $requestData['numPassengers'];

    $query = "SELECT * FROM flight WHERE departure_airport_id = '$destination' AND  DATE(depart_datetime) = '$departureDate' AND  TIME(depart_datetime) >= '$departureTime' AND DATE(arrival_datetime) = '$arrivalDate' AND TIME(arrival_datetime) <= '$arrivalTime' AND available_seats >= $numPassengers";

    $result = $mysqli->query($query);

    if ($result) {
        $flights = [];
        while ($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
        echo json_encode($flights);
    } else {
        echo json_encode(['error' => 'Failed to fetch flights']);
    }
} else {
    echo json_encode(['error' => 'Error']);
}
