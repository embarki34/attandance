<?php
include '../inc/nav.php';
include '../db.php';

// Initialize variables
$error_message = "";
$success_message = "";

// Check if the event ID is provided in the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Check if the event exists
    $check_event = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $check_event->bind_param("i", $event_id);
    $check_event->execute();
    $result = $check_event->get_result();

    if ($result->num_rows > 0) {
        // Event exists, proceed with deletion
        $delete_event = $conn->prepare("DELETE FROM events WHERE id = ?");
        $delete_event->bind_param("i", $event_id);

        if ($delete_event->execute()) {
            // Event deleted successfully
            $success_message = "Event deleted successfully!";
        } else {
            // Error in deleting event
            $error_message = "Error deleting event: " . $conn->error;
        }

        $delete_event->close();
    } else {
        // Event does not exist
        $error_message = "Error: Event not found!";
    }

    $check_event->close();
} else {
    // Event ID not provided
    header("Location: event_list.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>

<div class="container mx-auto my-6 p-4 bg-white shadow">
   
    <?php if ($success_message): ?>
        <p class="text-green-500"><?php echo $success_message; ?></p>
    <?php endif; ?>
    
    <a href="event_list.php" class="text-blue-500 mr-2">Back to Event List</a>
    <a href="delete_event.php?id=<?php echo $event_id; ?>
</div>

</body>
</html>
