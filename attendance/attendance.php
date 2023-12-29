<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST["event_id"];
    $student_data = json_decode($_POST["student_data"], true);

    foreach ($student_data as $student) {
        $student_id = $student["id"];

        $member_query = "SELECT * FROM `members` WHERE `student_id` = ?";
        $member_stmt = $conn->prepare($member_query);
        $member_stmt->bind_param("s", $student_id);
        $member_stmt->execute();
        $member_result = $member_stmt->get_result();
        $member_row = $member_result->fetch_assoc();
        $member_stmt->close();

        if ($member_row) {
            $query = "INSERT INTO `attendance` (`event_id`, `student_id`) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $event_id, $member_row["id"]);
            $stmt->execute();
            $stmt->close();

            $update_query = "UPDATE `members` SET `points` = `points` + 2 WHERE `id` = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $member_row["id"]);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }

   
    exit(); // Make sure to exit after sending the header

}

$eventsQuery = "SELECT * FROM `events`";
$eventsResult = $conn->query($eventsQuery);
$events = $eventsResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white">
        <div class="p-4">
            <h1 class="text-2xl font-bold">Admin Panel</h1>
        </div>
        <nav class="mt-4">
            <a href="http://localhost/attendance/" class="block p-3">Dashboard</a>
            <a href="http://localhost/attendance/event/event_list.php" class="block p-3">Events</a>
            <a href="http://localhost/attendance/attendance/attendance.php" class="block p-3">Attendance</a>
            <a href="http://localhost/attendance/members/member_list.php" class="block p-3">Members</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar -->


        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto p-8">
                <h1 class="text-3xl font-bold mb-8">Attendance Form</h1>
                <form id="attendanceForm" method="post" action="">

                    <div class="mb-4">
                        <label for="event" class="block text-sm font-medium text-gray-600">Select Event:</label>
                        <select name="event_id" id="event"
                            class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            <?php foreach ($events as $event): ?>
                                <option value="<?php echo $event['id']; ?>">
                                    <?php echo $event['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="student_id" class="block text-sm font-medium text-gray-600">Student ID:</label>
                        <input type="text" id="student_id"
                            class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        <button type="button" onclick="addStudent()"
                            class="ml-2 p-2 bg-blue-500 text-white rounded-md">Add
                            Student</button>
                    </div>

                    <input type="submit" value="Submit" class="p-2 bg-green-500 text-white rounded-md cursor-pointer">
                    <h2 class="text-2xl font-bold mb-4">Add Students</h2>
                    <table id="studentTable" class="w-full mb-4">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Student ID</th>
                                <th class="py-2 px-4 border-b">Name</th>
                                <th class="py-2 px-4 border-b">Family Name</th>
                                <th class="py-2 px-4 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be dynamically added here -->
                        </tbody>
                    </table>
                </form>
            </div>
        </main>
    </div>

    <script>
        var initialStudentIds = [];

        $(document).ready(function () {


            $("#attendanceForm").submit(function (event) {
                event.preventDefault();

                var formData = $(this).serializeArray();
                var studentData = [];

                $("#studentTable tbody tr").each(function () {
                    var studentId = $(this).find("td:first").text();
                    studentData.push({ id: studentId });
                });

                formData.push({ name: "student_data", value: JSON.stringify(studentData) });

                $.ajax({
                    type: "POST",
                    url: "attendance.php",
                    data: formData,
                    success: function (response) {
                        console.log(response);

                        // Redirect to the desired page using JavaScript
                        window.location.href = 'http://localhost/attendance/attendance/attendance.php';
                    },
                    error: function (error) {
                        console.error(error.responseText);
                    }
                });

            });
        });

        function checkDuplicateInTable(studentId) {
            var existingStudentIds = $("#studentTable tbody td:first-child").map(function () {
                return $(this).text();
            }).get();

            return $.inArray(studentId, existingStudentIds) !== -1;
        }

        function addStudent() {
            var studentId = $("#student_id").val().trim();

            if (studentId === "") {
                alert("Please enter a student ID.");
                return;
            }

            if (initialStudentIds.includes(studentId)) {
                alert("Student already added from the beginning");
                return;
            }

            if (checkDuplicateInTable(studentId)) {
                alert("Student already added");
                return;
            }

            // You can continue with the rest of the code for fetching member information
            $.ajax({
                type: "GET",
                url: "fetch_member.php",
                data: { student_id: studentId },
                dataType: "json",
                success: function (member) {
                    if (member) {
                        removeDuplicateRows(studentId);
                        $("#studentTable tbody").append('<tr><td>' + member.student_id + '</td><td>' + member.name + '</td><td>' + member.family_name + '</td><td><button type="button" onclick="removeStudent(this)">Remove</button></td></tr>');
                        $("#student_id").val("");
                    } else {
                        alert("Member not found");
                    }
                },
                error: function (error) {
                    console.error(error.responseText);
                }
            });
        }

        function removeStudent(button) {
            $(button).closest('tr').remove();
        }

        function removeDuplicateRows(studentId) {
            $("#studentTable tbody tr").each(function () {
                var existingStudentId = $(this).find("td:first").text();

                if (existingStudentId === studentId) {
                    $(this).remove();
                }
            });
        }

        function checkDuplicateInTable(studentId) {
            var existingStudentIds = $("#studentTable tbody td:first-child").map(function () {
                return $(this).text();
            }).get();

            return $.inArray(studentId, existingStudentIds) !== -1;
        }
    </script>
</body>

</html>