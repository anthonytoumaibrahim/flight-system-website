<?php
require_once("../config.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $data = getJSONPost();
  $status = "available";
  $sql = "INSERT INTO flight (airline_name, flight_number, depart_datetime, arrival_datetime, flight_price, available_seats, flight_status, departure_airport_id, aircraft_type_id, arrival_airport_id) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("siisiiiiii", $data['airline_name'], $data['flight_number'], $data['depart_datetime'], $data['arrival_datetime'], $data['flight_price'], $data['available_seats'], $status, $data['departure_airport_id'], $data['aircraft_type_id'], $data['arrival_airport_id']);

  if ($stmt->execute()) {
    echo json_encode(["message" => "Flight added successfully"]);
  } else {
    echo json_encode(["message" => "Failed to add flight"]);
  }
  exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
  $data = getJSONPost();

  $sql = "DELETE FROM flight WHERE flight_id=?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("i", $data['flight_id']);

  if ($stmt->execute()) {
    echo json_encode(["message" => "Flight deleted successfully"]);
  } else {
    echo json_encode(["message" => "Failed to delete flight"]);
  }
  exit();
} else {
  echo json_encode(["message" => "wrong method"]);
  exit();
}
