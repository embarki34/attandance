<!-- member_list.php -->
<?php
include '../inc/nav.php';
include '../db.php';

// Fetch members from the database (you need to implement the database connection)
// Example query: SELECT * FROM members
$members = mysqli_query($conn, "SELECT * FROM members");
?>
<body class="bg-white ">
<div class="container mx-auto my-6 p-4 bg-white shadow">
    <h2 class="text-xl font-bold mb-4">Member List</h2>
    <table class="w-full border">
        <thead>
            <tr>
                <th class="border p-2">ID</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Family Name</th>
                <th class="border p-2">Student ID</th>
                <th class="border p-2">Department</th>
                <th class="border p-2">Phone Number</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">points</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through the members and display them here -->
            <?php  Example: foreach ($members as $member):  ?>
                <tr>
                    <td class="border p-2"><?php  echo $member['id'];  ?></td>
                    <td class="border p-2"><?php  echo $member['name'];  ?></td>
                    <td class="border p-2"><?php  echo $member['family_name'];  ?></td>
                    <td class="border p-2"><?php  echo $member['student_id'];  ?></td>
                    <td class="border p-2"><?php  echo $member['department'];  ?></td>
                    <td class="border p-2"><?php  echo $member['phone_number'];  ?></td>
                    <td class="border p-2"><?php  echo $member['email'];  ?></td>
                    <td class="border p-2"><?php  echo $member['points'];  ?></td>
                    <td class="border p-2">
                        <a href="edit_member.php?id=<?php  echo $member['id'];  ?>" class="text-blue-500">Edit</a>
                        <a href="delete_member.php?id=<?php  echo $member['id'];  ?>" class="text-red-500 ml-2">Delete</a>
                    </td>
                </tr>
            <?php  endforeach;  ?>
        </tbody>
        <div class="container mx-auto my-6 p-4 bg-white shadow">
 
    <form action="add_member.php" method="">
        <!-- Add your form fields here (name, family_name, student_id, department, phone_number, email) -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Member</button>
    </form>
</div>

    </table>
</div>
</body>