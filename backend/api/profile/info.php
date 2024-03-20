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

$query = mysqli_query($mysqli, "SELECT c.fullname, c.gender, c.address, c.client_phonenumber, c.client_dob, SUM(co.amount) AS coins_amount FROM client c LEFT JOIN coins co on co.client_id = c.client_id AND co.status = 'accepted' WHERE c.client_id={$id}");
$data = $query->fetch_assoc();

$response['success'] = true;
$response['message'] = 'Fetched user info successfully.';
$response['data'] = $data;

echo json_encode($response);
