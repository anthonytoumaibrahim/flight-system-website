<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT booking.booking_id, flight.airline_name, flight.flight_number, flight.depart_datetime, flight.arrival_datetime, flight.flight_price, client.fullname, client.email, client.client_phonenumber, payment.status AS payment_status
            FROM booking
            INNER JOIN flight ON booking.flight_id = flight.flight_id
            INNER JOIN client ON booking.client_id = client.client_id
            LEFT JOIN payment ON booking.booking_id = payment.booking_id";
            
    $result = $mysqli->query($sql);
  
    if ($result->num_rows > 0) {
      $bookings = array();
      while ($row = $result->fetch_assoc()) {
        $booking_item = array(
          "booking_id" => $row["booking_id"],
          "airline_name" => $row["airline_name"],
          "flight_number" => $row["flight_number"],
          "depart_datetime" => $row["depart_datetime"],
          "arrival_datetime" => $row["arrival_datetime"],
          "flight_price" => $row["flight_price"],
          "fullname" => $row["fullname"],
          "email" => $row["email"],
          "client_phonenumber" => $row["client_phonenumber"],
          "payment_status" => $row["payment_status"]
        );
        array_push($bookings, $booking_item);
      }
      echo json_encode($bookings);
      exit();
    } else {
      http_response_code(404);
      echo json_encode(["message" => "No bookings found"]);
      exit();
    }
  }
  http_response_code(404);
  echo json_encode(["message" => "Endpoint not found"]);
  exit();
