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

// Initialize variables
$error_message = "";
$success_message = "";

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
            // Update member details
            $sql = "UPDATE members SET name=?, family_name=?, department=?, phone_number=?, email=? WHERE student_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $family_name, $department, $phone_number, $email, $student_id);

            if ($stmt->execute()) {
                // Member details updated successfully
                $success_message = "Member details updated successfully!";
                
            } else {
                // Error in updating member details
                $error_message = "Error updating member details: " . $conn->error;
            }

            $stmt->close();
        } else {
            $error_message = "Error updating member details: All fields are required";
        }
    }
}

// Fetch member details based on ID
if (isset($_GET['id'])) {
    $member_id = $_GET['id'];

    $select_query = "SELECT * FROM members WHERE id=?";
    $select_stmt = $conn->prepare($select_query);
    $select_stmt->bind_param("i", $member_id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $member_name = $row['name'];
        $member_family_name = $row['family_name'];
        $member_student_id = $row['student_id'];
        $member_department = $row['department'];
        $member_phone_number = $row['phone_number'];
        $member_email = $row['email'];
    } else {
        $error_message = "Error: Member not found!";
    }

    $select_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto my-6 p-4 bg-white shadow">
    <h2 class="text-xl font-bold mb-4">Edit Member</h2>
    <?php if ($error_message): ?>
        <p class="text-red-500"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <p class="text-green-500"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (isset($member_name)): ?>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="w-full border p-2" value="<?php echo $member_name; ?>">
            </div>
            <div class="mb-4">
                <label for="family_name" class="block text-gray-700">Family Name:</label>
                <input type="text" id="family_name" name="family_name" class="w-full border p-2" value="<?php echo $member_family_name; ?>">
            </div>
            <div class="mb-4">
                <label for="student_id" class="block text-gray-700">Student ID:</label>
                <input type="text" id="student_id" name="student_id" class="w-full border p-2" value="<?php echo $member_student_id; ?>" readonly>
            </div>
            <div class="mb-4">
                <label for="department" class="block text-gray-700">Department:</label>
                <input type="text" id="department" name="department" class="w-full border p-2" value="<?php echo $member_department; ?>">
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-gray-700">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" class="w-full border p-2" value="<?php echo $member_phone_number; ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="w-full border p-2" value="<?php echo $member_email; ?>">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Member</button>
        </form>
    <?php else: ?>
        <p class="text-red-500">Error: Member details not available for editing.</p>
    <?php endif; ?>
</div>

</body>
</html>
