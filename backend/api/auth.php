<?php
require_once("../config.php");

$response = [
  'success' => false,
  'message' => 'Unknown method',
  'data' => []
];

$data = getJSONPost();

$authType = $data['auth_type'] ?? "SIGNUP";
$name = $data['name'] ?? "";
$email = $data['email'] ?? "";
$password = $data['password'] ?? "";

// Sign up
if ($authType === "SIGNUP") {
  if ($name === "" || !preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email) || strlen($password) < 8) {
    $response['message'] = 'Please fix the errors before submitting the form.';
    exit(json_encode($response));
  }

  // Check if user already exists
  $check_query = $mysqli->prepare("SELECT client_id FROM client WHERE email=?");
  $check_query->bind_param("s", $email);
  $check_query->execute();
  $check_query->store_result();
  $rows = $check_query->num_rows();
  if ($rows !== 0) {
    $response['message'] = 'This email is already in use. Please try logging in instead.';
    exit(json_encode($response));
  }

  // Create user
  $password = password_hash($password, PASSWORD_BCRYPT);
  $create_query = $mysqli->prepare("INSERT INTO client (fullname, email, password) VALUES (?, ?, ?)");
  $create_query->bind_param("sss", $name, $email, $password);
  $create_query->execute();
  $create_query->store_result();


  if ($create_query->affected_rows === -1) {
    $response['message'] = 'Sorry, something went wrong. Please try again.';
    exit(json_encode($response));
  }

  // Created account successfully
  $id = $create_query->insert_id;
  $response['success'] = true;
  $response['message'] = 'Account created successfully.';
  $response['data'] = [
    'id' => $id,
    'token' => makeJWT([
      'user_id' => $id
    ]),
    'role' => 'user'
  ];
  exit(json_encode($response));
}

// Login
if ($authType === "LOGIN") {

  // Check if email exists
  $check_query = $mysqli->prepare("SELECT client.client_id, client.password, role.name AS role_name FROM client, role WHERE client.email=? AND role.role_id=client.role_id");
  $check_query->bind_param("s", $email);
  $check_query->execute();
  $check_query->store_result();
  $rows = $check_query->num_rows;

  if ($rows === 0) {
    $response['message'] = 'This email does not exist. Try creating an account.';
    exit(json_encode($response));
  }

  $check_query->bind_result($id, $hashed_password, $role_name);
  $check_query->fetch();

  // Check password
  if (!password_verify($password, $hashed_password)) {
    $response['message'] = 'Invalid credentials.';
    exit(json_encode($response));
  }

  // Login successful
  $response['success'] = true;
  $response['message'] = 'Logged in successfully.';
  $response['data'] = [
    'id' => $id,
    'token' => makeJWT([
      'user_id' => $id
    ]),
    'role' => $role_name
  ];
  exit(json_encode($response));
}

echo json_encode($response);
