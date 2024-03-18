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

$query = mysqli_query($mysqli, "SELECT fullname, email, gender, address, client_phonenumber, client_dob FROM client WHERE client_id={$id}");
$data = $query->fetch_assoc();

$response['success'] = true;
$response['message'] = 'Fetched user info successfully.';
$response['data'] = $data;

echo json_encode($response);
