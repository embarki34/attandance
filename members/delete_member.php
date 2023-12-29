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

// Handle member deletion
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $member_id = $_GET['id'];

    // Check if the member exists
    $check_member = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $check_member->bind_param("i", $member_id);
    $check_member->execute();
    $result = $check_member->get_result();

    if ($result->num_rows > 0) {
        // Member exists, proceed with deletion
        $delete_member = $conn->prepare("DELETE FROM members WHERE id = ?");
        $delete_member->bind_param("i", $member_id);

        if ($delete_member->execute()) {
            // Member deleted successfully
            $success_message = "Member deleted successfully!";
        } else {
            // Error in deleting member
            $error_message = "Error deleting member: " . $conn->error;
        }

        $delete_member->close();
    } else {
        // Member does not exist
        $error_message = "Error: Member not found!";
    }

    $check_member->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Member</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto my-6 p-4 bg-white shadow">
    <h2 class="text-xl font-bold mb-4">Delete Member</h2>
    <?php if ($error_message): ?>
        <p class="text-red-500"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <p class="text-green-500"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <p class="mb-4">Are you sure you want to delete this member?</p>
    <form action="" method="GET">
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete Member</button>
    </form>
    <a href="member_list.php" class="text-blue-500">Back to Member List</a>
</div>

</body>
</html>
