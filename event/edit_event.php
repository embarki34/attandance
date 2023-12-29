<?php
include '../inc/nav.php';
include '../db.php';

// Initialize variables
$error_message = "";
$success_message = "";

// Check if the event ID is provided in the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event details from the database
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        // Event not found
        header("Location: event_list.php");
        exit();
    }

    $stmt->close();
} else {
    // Event ID not provided
    header("Location: event_list.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $date = $_POST['date'];

    // Update event details in the database
    $update_sql = "UPDATE events SET name = ?, location = ?, date = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $name, $location, $date, $event_id);

    if ($update_stmt->execute()) {
        // Event updated successfully
        $success_message = "Event updated successfully!";
    } else {
        // Error in updating event
        $error_message = "Error updating event: " . $conn->error;
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto my-6 p-4 bg-white shadow">
    <h2 class="text-xl font-bold mb-4">Edit Event</h2>
    <?php if ($error_message): ?>
        <p class="text-red-500"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <p class="text-green-500"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Event Name:</label>
            <input type="text" id="name" name="name" class="w-full border p-2" value="<?php echo $event['name']; ?>">
        </div>
        <div class="mb-4">
            <label for="location" class="block text-gray-700">Location:</label>
            <input type="text" id="location" name="location" class="w-full border p-2" value="<?php echo $event['location']; ?>">
        </div>
        <div class="mb-4">
            <label for="date" class="block text-gray-700">Date:</label>
            <input type="date" id="date" name="date" class="w-full border p-2" value="<?php echo $event['date']; ?>">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Event</button>
    </form>
    <a href="event_list.php" class="text-blue-500 mt-4">Back to Event List</a>
</div>

</body>
</html>
