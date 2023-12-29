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
$pointsData = [];

$topMembersQuery = "SELECT m.name AS member_name, m.points
                    FROM members m
                    ORDER BY m.points DESC
                    LIMIT 10";
$topMembersResult = $conn->query($topMembersQuery);

while ($row = $topMembersResult->fetch_assoc()) {
    $labels[] = $row['member_name'];
    $pointsData[] = $row['points'];
}

$response = [
    'labels' => $labels,
    'pointsData' => $pointsData,
];

header('Content-Type: application/json');
echo json_encode($response);
?>
