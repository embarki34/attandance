<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events count
$eventsCountQuery = "SELECT COUNT(*) AS count FROM `events`";
$eventsCountResult = $conn->query($eventsCountQuery);
$eventsCount = $eventsCountResult->fetch_assoc()['count'];

// Fetch members count
$membersCountQuery = "SELECT COUNT(*) AS count FROM `members`";
$membersCountResult = $conn->query($membersCountQuery);
$membersCount = $membersCountResult->fetch_assoc()['count'];

// Fetch information about the best member
$bestMemberQuery = "SELECT `name`, `points` FROM `members` ORDER BY `points` DESC LIMIT 1";
$bestMemberResult = $conn->query($bestMemberQuery);
$bestMember = $bestMemberResult->fetch_assoc();

// Prepare data for JSON response
$response = [
    'eventsCount' => $eventsCount,
    'membersCount' => $membersCount,
    'bestMemberName' => isset($bestMember['name']) ? $bestMember['name'] : 'N/A',
    'bestMemberPoints' => isset($bestMember['points']) ? $bestMember['points'] : 0,
];

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
