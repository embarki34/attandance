<?php
include '../inc/nav.php';
include '../db.php';

// Fetch events from the database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

// Check for errors in query execution
if (!$result) {
    die("Error fetching events: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>

    <div class="container mx-auto my-6 p-4 bg-white shadow">
        <h2 class="text-xl font-bold mb-4">Event List</h2>
        <div class="container mx-auto my-6 p-4 bg-white shadow">

            <form action="add_event.php" method="">
                <!-- Add your form fields here (name, family_name, student_id, department, phone_number, email) -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add event</button>
            </form>
        </div>

        <table class="min-w-full border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Location</th>
                    <th class="border border-gray-300 px-4 py-2">Date</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php echo $row['id']; ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php echo $row['name']; ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php echo $row['location']; ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php echo $row['date']; ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="text-green-500 ml-2">Edit</a>
                            <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="text-red-500 ml-2">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>


    </div>

</body>

</html>

<?php
// Close database connection
$conn->close();
?>