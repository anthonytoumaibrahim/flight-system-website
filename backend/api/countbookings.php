<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $sql = "SELECT COUNT(*) AS booking_count FROM booking";
  $result = $mysqli->query($sql);
  $row = $result->fetch_assoc();
  echo json_encode(["booking_count" => $row["booking_count"]]);
  exit();
}

http_response_code(404);
echo json_encode(["message" => "Endpoint not found"]);
exit();
