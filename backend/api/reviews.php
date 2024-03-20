<?php

include("config.php");

$rating = $_POST['rating'];
$timeStamp = $_POST['time_stamp_rev'];
$status = $_POST['status'];
$helpfulVotes = $_POST['helpful_votes'];
$reported = $_POST['reported'];
$flaggedReasong = $_POST['flagged_reason'];
$adminComment = $_POST['admin_comment'];
$clientId = $_POST['client_id'];
$flightId = $_POST['flight_id'];


function insertReview($rating, $timeStamp, $status, $helpfulVotes, $reported, $flaggedReason, $adminComment, $clientId, $flightId) {
    global $mysqli;
    
    $sql = "INSERT INTO review (rating, time_stamp_rev, status, helpful_votes, reported, flagged_reason, admin_comment, client_id, flight_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("iisiiisii", $rating, $timeStamp, $status, $helpfulVotes, $reported, $flaggedReason, $adminComment, $clientId, $flightId);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


function getReviewsByRating($rating) {
    global $mysqli;
    
    $sql = "SELECT * FROM review WHERE rating = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("i", $rating);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reviews = [];
        
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        return $reviews;
    } else {
        return [];
    }
}


function getReviewsByClientId($clientId) {
    global $mysqli;
    
    $sql = "SELECT * FROM review WHERE client_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("i", $clientId);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reviews = [];
        
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        return $reviews;
    } else {
        return [];
    }
}


function getReviewsByClientAndRating($clientId, $rating) {
    global $mysqli;
    
    $sql = "SELECT * FROM review WHERE client_id = ? AND rating = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("ii", $clientId, $rating);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reviews = [];
        
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        return $reviews;
    } else {
        return [];
    }
}


function getReviewsByStatus($status) {
    global $mysqli;
    
    $sql = "SELECT * FROM review WHERE status = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("s", $status);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reviews = [];
        
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        return $reviews;
    } else {
        return [];
    }
}


function updateAdminComment($reviewId, $adminComment) {
    global $mysqli;
    
    $sql = "UPDATE review SET admin_comment = ? WHERE review_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("si", $adminComment, $reviewId);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


function deleteReviewsByFlight($flightId) {
    global $mysqli;
    
    $sql = "DELETE FROM review WHERE flight_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("i", $flightId);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


function deleteReviewsByClient($clientId) {
    global $mysqli;
    
    $sql = "DELETE FROM review WHERE client_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("i", $clientId);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}



function deleteAllReviews() {
    global $mysqli;
    
    $sql = "DELETE FROM review";
    
    $result = $mysqli->query($sql);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rating'])) {
    $rating = $_GET['flight_id'];
    $reviewrating = getReviewsByRating($rating);
    header('Content-Type: application/json');
    echo json_encode($reviewrating);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['client_id'])) {
    $reviewclient = getReviewsByClientId($clientId);
    header('Content-Type: application/json');
    echo json_encode($reviewclient);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status'])) {
    $reviewstatus = getReviewsByStatus($status);
    header('Content-Type: application/json');
    echo json_encode($reviewstatus);
}



?>