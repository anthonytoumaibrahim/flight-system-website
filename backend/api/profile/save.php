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

$name = $data['name'] ?? "";
$gender = $data['gender'] ?? "";
$address = $data['address'] ?? "";
$phone = $data['phone'] ?? "";
$dob = $data['dob'] ?? "";

$save_query = $mysqli->prepare("UPDATE client SET fullname=?, gender=?, address=?, client_phonenumber=?, client_dob=? WHERE client_id=?");
$save_query->bind_param("sssssi", $name, $gender, $address, $phone, $dob, $id);
$save_query->execute();
$save_query->store_result();

if ($save_query->affected_rows === -1) {
  $response['message'] = 'Sorry, something went wrong. Please try again.';
  exit(json_encode($response));
}

// Save success
$response['success'] = true;
$response['message'] = 'Your settings have been saved.';

echo json_encode($response);
