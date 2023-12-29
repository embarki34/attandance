<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$labels = [];
$attendanceData = [];

$attendanceQuery = "SELECT e.name AS event_name, COUNT(a.id) AS attendance_count
                    FROM events e
                    LEFT JOIN attendance a ON e.id = a.event_id
                    GROUP BY e.id";
$attendanceResult = $conn->query($attendanceQuery);

while ($row = $attendanceResult->fetch_assoc()) {
    $labels[] = $row['event_name'];
    $attendanceData[] = $row['attendance_count'];
}

$response = [
    'labels' => $labels,
    'attendanceData' => $attendanceData,
];

header('Content-Type: application/json');
echo json_encode($response);
?>
