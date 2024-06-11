<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $category_id = $_POST['category_id'];

    $sql = "INSERT INTO reviews (title, review, rating, category_id) VALUES ('$title', '$review', '$rating', '$category_id')";
    if ($conn->query($sql) === TRUE) {
        echo "New review created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM reviews";
$result = $conn->query($sql);

$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Film Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Header -->
<div class="w-full h-20 bg-gray-800 flex items-center justify-around text-white">
    <a href="../Review/home.php">Home</a>
    <a href="categories.php">Manage Categories</a>
</div>

<!-- Main Content -->
<div class="w-full min-h-screen flex justify-center py-8">
    <!-- Input Review -->
    <div class="bg-white w-full max-w-md p-8 shadow-lg rounded-lg mx-4">
        <h2 class="text-2xl font-bold mb-6 text-center">Submit a New Film Review</h2>
        <form method="POST" action="" class="space-y-4">
            <input type="text" name="title" placeholder="Title" class="w-full p-2 border border-gray-300 rounded" required>
            <textarea name="review" placeholder="Review" class="w-full p-2 border border-gray-300 rounded" required></textarea>
            <input type="number" name="rating" min="1" max="5" placeholder="Rating" class="w-full p-2 border border-gray-300 rounded" required>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category:</label>
                <select name="category_id" id="category_id" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php
                    if ($categories->num_rows > 0) {
                        while ($cat = $categories->fetch_assoc()) {
                            echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Submit" class="w-full bg-green-500 text-white p-2 rounded cursor-pointer hover:bg-green-600">
        </form>
    </div>

    <!-- All Reviews Table -->
    <div class="bg-white w-full max-w-4xl p-8 shadow-lg rounded-lg mx-4">
        <h2 class="text-2xl font-bold mb-6 text-center">All Reviews</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200">ID</th>
                    <th class="py-2 px-4 bg-gray-200">Title</th>
                    <th class="py-2 px-4 bg-gray-200">Review</th>
                    <th class="py-2 px-4 bg-gray-200">Rating</th>
                    <th class="py-2 px-4 bg-gray-200">Category</th>
                    <th class="py-2 px-4 bg-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $category = $conn->query("SELECT name FROM categories WHERE id=" . $row['category_id'])->fetch_assoc();
                        echo "<tr class='hover:bg-gray-100'>";
                        echo "<td class='py-2 px-4'>" . $row['id'] . "</td>";
                        echo "<td class='py-2 px-4'>" . $row['title'] . "</td>";
                        echo "<td class='py-2 px-4'>" . $row['review'] . "</td>";
                        echo "<td class='py-2 px-4'>" . $row['rating'] . "</td>";
                        echo "<td class='py-2 px-4'>" . $category['name'] . "</td>";
                        echo "<td class='py-2 px-4'><a href='edit.php?id=" . $row['id'] . "' class='text-blue-500 hover:text-blue-700'>Edit</a> | <a href='delete.php?id=" . $row['id'] . "' class='text-red-500 hover:text-red-700'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-4'>No reviews found</td></tr>";
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
