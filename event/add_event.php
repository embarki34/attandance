<?php

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
    $name = $_POST['name'];
    $location = $_POST['location'];
    $date = $_POST['date'];

    // Insert new event into the database
    $insert_sql = "INSERT INTO events (name, location, date) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sss", $name, $location, $date);

    if ($insert_stmt->execute()) {
        // Event added successfully
        $success_message = "Event added successfully!";
        header("Location: events_list.php");
    } else {
        // Error in adding event
        $error_message = "Error adding event: " . $conn->error;
    }

    $insert_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

</head>

<body>
<?php include '../inc/nav.php';?>

    <div class="container mx-auto my-6 p-4 bg-white shadow">
        <h2 class="text-xl font-bold mb-4">Add Event</h2>
        <?php if ($error_message): ?>
            <p class="text-red-500">
                <?php echo $error_message; ?>
            </p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="text-green-500">
                <?php echo $success_message; ?>
            </p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Event Name:</label>
                <input type="text" id="name" name="name" class="w-full border p-2">
            </div>
            <div class="mb-4">
                <label for="location" class="block text-gray-700">Location:</label>
                <input type="text" id="location" name="location" class="w-full border p-2">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700">Date:</label>
                <input type="date" id="date" name="date" class="w-full border p-2">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Event</button>
        </form>

        <a href="event_list.php" class="text-blue-500 mt-4">Back to Event List</a>
    </div>

</body>

</html>