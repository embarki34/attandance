<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white">
        <div class="p-4">
            <h1 class="text-2xl font-bold">Admin Panel</h1>
        </div>
        <nav class="mt-4">
            <a href="attendance/index.php" class="block p-3">Dashboard</a>
            <a href="attendance/event/event_list.php" class="block p-3">Events</a>
            <a href="attendance/attendance/attendance.php" class="block p-3">Attendance</a>
            <a href="attendance/members/member_list.php" class="block p-3">Members</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar -->
        <header class="bg-white shadow">
            <div class="container mx-auto py-4">
                <h1 class="text-2xl font-bold ml-5">Admin Dashboard</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <!-- Your page content goes here -->
        </main>
    </div>
</body>
</html>
