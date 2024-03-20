<?php
require_once("../config.php");

// Verify user
$id = verifyToken();

if (!$id) {
    exit(json_encode(['error' => 'Unauthorized user.']));
}

// First, prompt the user to fill their personal information
// if they haven't yet
$allowed_to_search = false;
$res = mysqli_query($mysqli, "SELECT fullname, gender, address, client_phonenumber, client_dob FROM client WHERE client_id={$id}");
$checks = $res->fetch_assoc();
if ($checks) {
    $allowed_to_search = true;
    foreach ($checks as $check) {
        if ($check === "" || $check === null) {
            $allowed_to_search = false;
        }
    }
}

if (!$allowed_to_search) {
    exit(json_encode(['error' => 'Please make sure to fill in all your personal information before searching for flights! <a href="./pages/profile.html">Fill out information now</a>']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $requestData = getJSONPost();

    $destination = $requestData['destination'];
    $departureDate = $requestData['departureDate'];
    $departureTime = $requestData['departureTime'];
    $arrivalDate = $requestData['arrivalDate'];
    $arrivalTime = $requestData['arrivalTime'];
    $numPassengers = $requestData['numPassengers'];

    $query = $mysqli->prepare("SELECT f.*, a.airport_name FROM flight f, airport a WHERE a.city = ? AND  DATE(f.depart_datetime) = ? AND TIME(f.depart_datetime) >= ? AND DATE(f.arrival_datetime) = ? AND TIME(f.arrival_datetime) <= ? AND f.available_seats >= ?");
    $query->bind_param("sssssi", $destination, $departureDate, $departureTime, $arrivalDate, $arrivalTime, $numPassengers);
    $query->execute();
    $query->store_result();
    $query->bind_result($id, $airline_name, $flight_number, $depart_datetime, $arrival_datetime, $flight_price, $available_seats, $flight_status, $departure_airport_id, $aircraft_type_id, $arrival_airport_id, $airport_name);

    $flights = [];
    while ($query->fetch()) {
        $flights[] = [
            'id' => $id,
            'airline_name' => $airline_name,
            'flight_number' => $flight_number,
            'depart_datetime' => $depart_datetime,
            'arrival_datetime' => $arrival_datetime,
            'flight_price' => $flight_price,
            'available_seats' => $available_seats,
            'flight_status' => $flight_status,
            'departure_airport_id' => $departure_airport_id,
            'aircraft_type_id' => $aircraft_type_id,
            'arrival_airport_id' => $arrival_airport_id,
            'airport_name' => $airport_name
        ];
    }

    if (count($flights) === 0) {
        echo json_encode(['error' => 'Couldn\'t find flights with this search criteria.']);
    } else {
        echo json_encode($flights);
    }
} else {
    echo json_encode(['error' => 'Error']);
}
