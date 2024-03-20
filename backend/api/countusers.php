<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $sql = "SELECT COUNT(*) AS user_count FROM client";
  $result = $mysqli->query($sql);
  $row = $result->fetch_assoc();
  echo json_encode(["user_count" => $row["user_count"]]);
  exit();
}

http_response_code(404);
echo json_encode(["message" => "Endpoint not found"]);
exit();
