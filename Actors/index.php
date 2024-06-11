<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $sql = "INSERT INTO actors (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        echo "New actor created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM actors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Film Actors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Header -->
<div class="w-full h-20 bg-gray-800 flex items-center justify-around text-white">
    <a href="../Review/index.php">Back to Reviews</a>
</div>

<!-- Main Content -->
<div class="w-full min-h-screen flex justify-center py-8">
    <!-- Input Actor -->
    <div class="bg-white w-full max-w-md p-8 shadow-lg rounded-lg mx-4">
        <h2 class="text-2xl font-bold mb-6 text-center">Add a New Actor</h2>
        <form method="POST" action="" class="space-y-4">
            <input type="text" name="name" placeholder="Actor Name" class="w-full p-2 border border-gray-300 rounded" required>
            <input type="submit" value="Submit" class="w-full bg-green-500 text-white p-2 rounded cursor-pointer hover:bg-green-600">
        </form>
    </div>

    <!-- All Actors Table -->
    <div class="bg-white w-full max-w-4xl p-8 shadow-lg rounded-lg mx-4">
        <h2 class="text-2xl font-bold mb-6 text-center">All Actors</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200">ID</th>
                    <th class="py-2 px-4 bg-gray-200">Name</th>
                    <th class="py-2 px-4 bg-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='hover:bg-gray-100'>";
                        echo "<td class='py-2 px-4'>" . $row['id'] . "</td>";
                        echo "<td class='py-2 px-4'>" . $row['name'] . "</td>";
                        echo "<td class='py-2 px-4'><a href='edit_actor.php?id=" . $row['id'] . "' class='text-blue-500 hover:text-blue-700'>Edit</a> | <a href='delete_actor.php?id=" . $row['id'] . "' class='text-red-500 hover:text-red-700'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center py-4'>No actors found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>
