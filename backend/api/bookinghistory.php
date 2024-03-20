<?php
require_once("../config.php");

// Verify user
$id = verifyToken();

if (!$id) {
    exit(json_encode(['error' => 'Unauthorized user.']));
}

function getAllBookings()
{
    global $mysqli;

    $sql = "SELECT * FROM booking";

    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    } else {
        return [];
    }
}

function getBookingsByClientId($clientId)
{
    global $mysqli;

    $sql = "SELECT b.*, f.* FROM booking b, flight f WHERE b.client_id = ? AND f.flight_id = b.flight_id";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("i", $clientId);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bookings = [];

        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    } else {
        return [];
    }
}


function getBookingsByFlightId($flightId)
{
    global $mysqli;

    $sql = "SELECT * FROM booking WHERE flight_id = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("i", $flightId);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bookings = [];

        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    } else {
        return [];
    }
}

function getTotNumOfBook()
{
    global $mysqli;

    $sql = "SELECT COUNT(*) as total FROM booking";

    $result = $mysqli->query($sql);

    $row = $result->fetch_assoc();

    return $row['total'];
}


function getNumBookByClient($clientId)
{
    global $mysqli;

    $sql = "SELECT COUNT(*) as total FROM booking WHERE client_id = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("i", $clientId);

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    return $row['total'];
}

function getNumberOfBookingsByFlightId($flightId)
{
    global $mysqli;

    $sql = "SELECT COUNT(*) as total FROM booking WHERE flight_id = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("i", $flightId);

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    return $row['total'];
}


function clearAllBookings()
{
    global $mysqli;

    $sql = "DELETE FROM booking";

    $result = $mysqli->query($sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['flight_id'])) {
    $flightId = $_GET['flight_id'];
    $flightbookings = getBookingsByFlightId($flightId);
    header('Content-Type: application/json');
    echo json_encode($flightbookings);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_bookings'])) {
    $clientbookings = getBookingsByClientId($id);
    header('Content-Type: application/json');
    echo json_encode($clientbookings);
}
