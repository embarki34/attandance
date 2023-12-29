<?php
// Include your database connection file or establish a connection here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if student_id parameter is provided
if (isset($_GET["student_id"])) {
    $student_id = $_GET["student_id"];

    // Fetch member information based on student ID
    $query = "SELECT * FROM `members` WHERE `student_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();

    // Return member information as JSON
    header('Content-Type: application/json');
    echo json_encode($member);
} else {
    // Invalid request without student_id parameter
    header('HTTP/1.1 400 Bad Request');
    echo "Invalid request";
}
?>
