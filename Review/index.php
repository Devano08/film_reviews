<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $category_id = $_POST['category_id'];
    $director_id = $_POST['director_id'];
    $actor_ids = $_POST['actor_ids'];

    $sql = "INSERT INTO reviews (title, review, rating, category_id, director_id) VALUES ('$title', '$review', '$rating', '$category_id', '$director_id')";
    if ($conn->query($sql) === TRUE) {
        $review_id = $conn->insert_id;
        foreach ($actor_ids as $actor_id) {
            $conn->query("INSERT INTO review_actors (review_id, actor_id) VALUES ('$review_id', '$actor_id')");
        }
        echo "New review created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT reviews.*, categories.name AS category_name, directors.name AS director_name FROM reviews LEFT JOIN categories ON reviews.category_id = categories.id LEFT JOIN directors ON reviews.director_id = directors.id";
$result = $conn->query($sql);

$categories = $conn->query("SELECT * FROM categories");
$directors = $conn->query("SELECT * FROM directors");
$actors = $conn->query("SELECT * FROM actors");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Film Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script type="text/javascript">
        function confirmDelete(reviewId) {
            if (confirm("Are you sure you want to delete this review?")) {
                window.location.href = 'delete.php?id=' + reviewId;
            }
        }
    </script>
</head>

<body>
    <!-- Header -->
    <div class="w-full h-20 bg-gray-800 flex items-center justify-around text-white">
        <a href="../Review/home.php">Home</a>
        <a href="../Kategori/index.php">Manage Categories</a>
        <a href="../Actors/index.php">Manage Actors</a>
        <a href="../Sutradara/index.php">Manage Directors</a>
    </div>
    <!-- Main Content -->
    <div class="w-full min-h-screen bg-gray-100 flex">
        <!-- Input Review -->
        <div class="bg-white w-1/3 p-8 shadow-lg m-4 rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Submit a New Film Review</h2>
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
                <div>
                    <label for="director_id" class="block text-sm font-medium text-gray-700">Director:</label>
                    <select name="director_id" id="director_id" class="w-full p-2 border border-gray-300 rounded" required>
                        <?php
                        if ($directors->num_rows > 0) {
                            while ($dir = $directors->fetch_assoc()) {
                                echo "<option value='" . $dir['id'] . "'>" . $dir['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="actor_ids" class="block text-sm font-medium text-gray-700">Actors:</label>
                    <select name="actor_ids[]" id="actor_ids" class="w-full p-2 border border-gray-300 rounded" multiple required>
                        <?php
                        if ($actors->num_rows > 0) {
                            while ($actor = $actors->fetch_assoc()) {
                                echo "<option value='" . $actor['id'] . "'>" . $actor['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <input type="submit" value="Submit" class="w-full bg-green-500 text-white p-2 rounded cursor-pointer hover:bg-green-600">
            </form>
        </div>

        <!-- All Reviews Table -->
        <div class="bg-white w-2/3 p-8 shadow-lg m-4 rounded-lg">
            <h2 class="text-2xl font-bold mb-6">All Reviews</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200">ID</th>
                        <th class="py-2 px-4 bg-gray-200">Title</th>
                        <th class="py-2 px-4 bg-gray-200">Review</th>
                        <th class="py-2 px-4 bg-gray-200">Rating</th>
                        <th class="py-2 px-4 bg-gray-200">Category</th>
                        <th class="py-2 px-4 bg-gray-200">Director</th>
                        <th class="py-2 px-4 bg-gray-200">Actors</th>
                        <th class="py-2 px-4 bg-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $review_id = $row['id'];
                            $actors_result = $conn->query("SELECT actors.name FROM review_actors LEFT JOIN actors ON review_actors.actor_id = actors.id WHERE review_actors.review_id = $review_id");
                            $actor_names = [];
                            while ($actor = $actors_result->fetch_assoc()) {
                                $actor_names[] = $actor['name'];
                            }
                            echo "<tr class='hover:bg-gray-100'>";
                            echo "<td class='py-2 px-4'>" . $row['id'] . "</td>";
                            echo "<td class='py-2 px-4'>" . $row['title'] . "</td>";
                            echo "<td class='py-2 px-4'>" . $row['review'] . "</td>";
                            echo "<td class='py-2 px-4'>" . $row['rating'] . "</td>";
                            echo "<td class='py-2 px-4'>" . $row['category_name'] . "</td>";
                            echo "<td class='py-2 px-4'>" . $row['director_name'] . "</td>";
                            echo "<td class='py-2 px-4'>" . implode(", ", $actor_names) . "</td>";
                            echo "<td class='py-2 px-4'><a href='edit.php?id=" . $row['id'] . "' class='text-blue-500 hover:text-blue-700'>Edit</a> | <a href='#' onclick='confirmDelete(" . $row['id'] . ")' class='text-red-500 hover:text-red-700'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center py-4'>No reviews found</td></tr>";
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
