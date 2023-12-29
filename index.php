<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$eventsQuery = "SELECT * FROM `events`";
$eventsResult = $conn->query($eventsQuery);
$events = $eventsResult->fetch_all(MYSQLI_ASSOC);

// Fetch attendance data for the "Attendance by Event" chart
$attendanceData = fetchAttendanceData($conn);

// Fetch top members data for the "Top 10 Members Based on Points" chart
$topMembersData = fetchTopMembersData($conn);

function fetchAttendanceData($conn)
{
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

    return [
        'labels' => $labels,
        'attendanceData' => $attendanceData,
    ];
}

function fetchTopMembersData($conn)
{
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

    return [
        'labels' => $labels,
        'pointsData' => $pointsData,
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white">
        <div class="p-4">
            <h1 class="text-2xl font-bold">Admin Panel</h1>
        </div>
        <nav class="mt-4">
            <a href="http://localhost/attendance/index.php" class="block p-3">Dashboard</a>
            <a href="http://localhost/attendance/event/event_list.php" class="block p-3">Events</a>
            <a href="http://localhost/attendance/attendance/attendance.php" class="block p-3">Attendance</a>
            <a href="http://localhost/attendance/members/member_list.php" class="block p-3">Members</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto p-8">
                <h1 class="text-3xl font-bold mb-8">University Dashboard</h1>

                <!-- Attendance by Event Chart -->
                <div>
                    <h2 class="text-2xl font-bold mb-4">Events Count</h2>
                    <p id="eventsCount">Loading...</p>
                </div>

                <!-- Members Count -->
                <div class="mt-4">
                    <h2 class="text-2xl font-bold mb-4">Members Count</h2>
                    <p id="membersCount">Loading...</p>
                </div>

                <!-- Best Member -->
                <div class="mt-4">
                    <h2 class="text-2xl font-bold mb-4">Best Member</h2>
                    <p id="bestMember">Loading...</p>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-4">Attendance by Event</h2>
                    <canvas id="attendanceChart" width="800" height="400"></canvas>
                </div>

                <!-- Top 10 Members Based on Points Chart -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Top 10 Members Based on Points</h2>
                    <canvas id="topMembersChart" width="800" height="400"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch data for the "Attendance by Event" chart
            $.ajax({
                type: "GET",
                url: "fetch_attendance_data.php", // Adjust the URL to your backend endpoint
                dataType: "json",
                success: function (attendanceData) {
                    createAttendanceChart(attendanceData);
                },
                error: function (error) {
                    console.error(error.responseText);
                }
            });

            // Fetch data for the "Top 10 Members Based on Points" chart
            $.ajax({
                type: "GET",
                url: "fetch_top_members_data.php", // Adjust the URL to your backend endpoint
                dataType: "json",
                success: function (topMembersData) {
                    createTopMembersChart(topMembersData);
                },
                error: function (error) {
                    console.error(error.responseText);
                }
            });

            // Fetch additional information (events count, members count, best member)
            $.ajax({
                type: "GET",
                url: "fetch_additional_info.php", // Adjust the URL to your backend endpoint
                dataType: "json",
                success: function (additionalInfo) {
                    updateAdditionalInfo(additionalInfo);
                },
                error: function (error) {
                    console.error(error.responseText);
                }
            });
        });
        function updateAdditionalInfo(data) {
            // Update events count
            $("#eventsCount").text("Events Count: " + data.eventsCount);

            // Update members count
            $("#membersCount").text("Members Count: " + data.membersCount);

            // Update best member
            $("#bestMember").text("Best Member: " + data.bestMemberName + " (Points: " + data.bestMemberPoints + ")");
        }

        function createAttendanceChart(data) {
            var ctx = document.getElementById('attendanceChart').getContext('2d');
            var attendanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Attendance by Event',
                        data: data.attendanceData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: false,  // Set to false to disable automatic resizing
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'category',
                            labels: data.labels
                        },
                        y: {
                            beginAtZero: true,
                            max: Math.max(...data.attendanceData) + 1
                        }
                    }
                }
            });
        }

        function createTopMembersChart(data) {
            var ctx = document.getElementById('topMembersChart').getContext('2d');
            var topMembersChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Top 10 Members Based on Points',
                        data: data.pointsData,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: false,  // Set to false to disable automatic resizing
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'category',
                            labels: data.labels
                        },
                        y: {
                            beginAtZero: true,
                            max: Math.max(...data.pointsData) + 1
                        }
                    }
                }
            });
        }

    </script>

</body>

</html>