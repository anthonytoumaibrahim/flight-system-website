<?php

include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT COUNT(*) AS user_count FROM client";
    
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt) {
        if ($stmt->execute()) {

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            

            echo json_encode(["user_count" => $row["user_count"]]);
            exit();
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to execute query"]);
            exit();
        }
        
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to prepare statement"]);
        exit();
    }
}

http_response_code(404);
echo json_encode(["message" => "Endpoint not found"]);
exit();
