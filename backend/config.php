<?php
// Fix CORS error
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

header("Content-Type: application/json");

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "flights_db";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
  exit("Connection to MySQL failed. Error: " . $mysqli->connect_error);
}

// This function allows us to send request data in JSON format
function getJSONPost(): array
{
  return json_decode(file_get_contents('php://input'), true);
}

// JWT: https://dev.to/robdwaller/how-to-create-a-json-web-token-using-php-3gml
function makeJWT(array|null $payload = []): string
{
  $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
  $payload = json_encode($payload);
  $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
  $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abcDEF123098', true);
  $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

  return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verifyToken(): string|bool
{
  $token = $_SERVER['HTTP_AUTHORIZATION'] ?? "";
  $parts = explode(".", $token);
  if (count($parts) < 3) {
    return false;
  }
  $payload = json_decode(base64_decode($parts[1]), true);
  $ver = makeJWT($payload);
  if ($ver === $token) {
    return $payload['user_id'];
  }
  return false;
}
