<?php
require_once("../../config.php");

$response = [
  'success' => false,
  'message' => '',
  'data' => []
];

// Verify user
$id = verifyToken();

if (!$id) {
  $response['message'] = 'Unauthorized user.';
  exit(json_encode($response));
}

$data = getJSONPost();

$amount = $data['amount'] ?? 0;
if ($amount <= 0) {
  $response['message'] = 'Invalid amount.';
  exit(json_encode($response));
}

$timestamp = time();
$status = "sent";

$query = $mysqli->prepare("INSERT INTO coins (amount, status, timestamp, client_id) VALUES (?, ?, ?, ?)");
$query->bind_param("issi", $amount, $status, $timestamp, $id);
$query->execute();
$query->store_result();
$num_rows = $query->affected_rows;

if ($num_rows === -1) {
  $response['message'] = 'Sorry, something went wrong. Your request couldn\'t be submitted.';
  exit(json_encode($response));
}

// Success
$response['success'] = true;
$response['message'] = "Your request has been successfully saved.";

echo json_encode($response);
