<?php

include("config.php");


$question = $_POST['question'];
$answer = $_POST['answer'];
$category = $_POST['category'];
$timeStamp = $_POST['time_stamp_faq'];
$clientId = $_POST['client_id'];

function insertFAQ($question, $answer, $category, $timeStamp, $clientId) {
    global $mysqli;
    
    $sql = "INSERT INTO faq (question, answer, category, time_stamp_faq, client_id) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("sssii", $question, $answer, $category, $timeStamp, $clientId);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getFAQsByClientId($clientId) {
    global $mysqli;
    
    $sql = "SELECT * FROM faq WHERE client_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("i", $clientId);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $faqs = [];
        
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
        
        return $faqs;
    } else {
        return [];
    }
}

function getFAQsByCategory($category) {
    global $mysqli;
    
    $sql = "SELECT * FROM faq WHERE category = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("s", $category);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $faqs = [];
        
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
        
        return $faqs;
    } else {
        return [];
    }
}

function getTotalNumberOfFAQs() {
    global $mysqli;
    
    $sql = "SELECT COUNT(*) as total FROM faq";
    
    $result = $mysqli->query($sql);
    
    $row = $result->fetch_assoc();
    
    return $row['total'];
}

function getNumberOfFAQsByClient($clientId) {
    global $mysqli;
    
    $sql = "SELECT COUNT(*) as total FROM faq WHERE client_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("i", $clientId);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    
    return $row['total'];
}


function getNumberOfFAQsByCategory($category) {
    global $mysqli;
    
    $sql = "SELECT COUNT(*) as total FROM faq WHERE category = ?";
    
    $stmt = $mysqli->prepare($sql);
    
    $stmt->bind_param("s", $category);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    
    return $row['total'];
}

function clearAllFAQs() {
    global $mysqli;
    
    $sql = "DELETE FROM faq";
    
    $result = $mysqli->query($sql);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category'])) {
    $category = $_GET['category'];
    $faqscategory = getFAQsByCategory($category);
    header('Content-Type: application/json');
    echo json_encode($faqscategory);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['client_id'])) {
    $clientId = $_GET['client_id']; 
    $clientfaqs = getFAQsByClientId($clientId);
    header('Content-Type: application/json');
    echo json_encode($clientfaqs);
}

?>
