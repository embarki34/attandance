<?php
include '../inc/nav.php';

// Database connection
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if form fields are set
    if (
        isset($_POST['name']) &&
        isset($_POST['family_name']) &&
        isset($_POST['student_id']) &&
        isset($_POST['department']) &&
        isset($_POST['phone_number']) &&
        isset($_POST['email'])
    ) {
        // Sanitize form data
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $family_name = filter_var($_POST['family_name'], FILTER_SANITIZE_STRING);
        $student_id = filter_var($_POST['student_id'], FILTER_SANITIZE_STRING);
        $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
        $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Check if form fields are not empty
        if (!empty($name) && !empty($family_name) && !empty($student_id) && !empty($department) && !empty($phone_number) && !empty($email)) {
            // Check for duplicate student ID
            $check_duplicate = $conn->prepare("SELECT id FROM members WHERE student_id = ?");
            $check_duplicate->bind_param("s", $student_id);
            $check_duplicate->execute();
            $check_duplicate_result = $check_duplicate->get_result();

            if ($check_duplicate_result->num_rows > 0) {
                $error_message = "Error adding member: Duplicate student ID";
            } else {
                // Insert new member
                $sql = "INSERT INTO members (name, family_name, student_id, department, phone_number, email) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $name, $family_name, $student_id, $department, $phone_number, $email);

                if ($stmt->execute()) {
                    // Member added successfully
                    header("Location: member_list.php");
                    exit();
                } else {
                    // Error in adding member
                    $error_message = "Error adding member: " . $conn->error;
                }

                $stmt->close();
            }

            $check_duplicate->close();
        } else {
            $error_message = "Error adding member: All fields are required";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>



<div class="container mx-auto my-6 p-4 bg-white shadow">
    <h2 class="text-xl font-bold mb-4">Add Member</h2>
    <?php if (isset($error_message)): ?>
        <p class="text-red-500">
            <?php echo $error_message; ?>
        </p>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="w-full border p-2">
        </div>
        <div class="mb-4">
            <label for="family_name" class="block text-gray-700">Family Name:</label>
            <input type="text" id="family_name" name="family_name" class="w-full border p-2">
        </div>
        <div class="mb-4">
            <label for="student_id" class="block text-gray-700">Student ID:</label>
            <input type="text" id="student_id" name="student_id" class="w-full border p-2">
        </div>
        <div class="mb-4">
            <label for="department" class="block text-gray-700">Department:</label>
            <input type="text" id="department" name="department" class="w-full border p-2">
        </div>
        <div class="mb-4">
            <label for="phone_number" class="block text-gray-700">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" class="w-full border p-2">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="w-full border p-2">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Member</button>
    </form>
</div>

</body>
</html>
