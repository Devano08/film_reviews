<?php
include 'db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $category_id = $_POST['category_id'];
    $director_id = $_POST['director_id'];
    $actor_ids = $_POST['actor_ids'];

    $sql = "UPDATE reviews SET title='$title', review='$review', rating='$rating', category_id='$category_id', director_id='$director_id' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $conn->query("DELETE FROM review_actors WHERE review_id=$id");
        foreach ($actor_ids as $actor_id) {
            $conn->query("INSERT INTO review_actors (review_id, actor_id) VALUES ('$id', '$actor_id')");
        }
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM reviews WHERE id=$id";
$result = $conn->query($sql);
$review = $result->fetch_assoc();

$categories = $conn->query("SELECT * FROM categories");
$directors = $conn->query("SELECT * FROM directors");
$actors = $conn->query("SELECT * FROM actors");
$selected_actors = $conn->query("SELECT actor_id FROM review_actors WHERE review_id=$id");
$selected_actor_ids = [];
while($actor = $selected_actors->fetch_assoc()) {
    $selected_actor_ids[] = $actor['actor_id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
</head>
<body>

<h2>Edit Review</h2>

<form method="POST" action="">
    Title: <input type="text" name="title" value="<?php echo $review['title']; ?>" required><br>
    Review: <textarea name="review" required><?php echo $review['review']; ?></textarea><br>
    Rating: <input type="number" name="rating" value="<?php echo $review['rating']; ?>" min="1" max="5" required><br>
    Category: 
    <select name="category_id" required>
        <?php
        if ($categories->num_rows > 0) {
            while($cat = $categories->fetch_assoc()) {
                $selected = $cat['id'] == $review['category_id'] ? 'selected' : '';
                echo "<option value='" . $cat['id'] . "' $selected>" . $cat['name'] . "</option>";
            }
        }
        ?>
    </select><br>
    Director:
    <select name="director_id" required>
        <?php
        if ($directors->num_rows > 0) {
            while($dir = $directors->fetch_assoc()) {
                $selected = $dir['id'] == $review['director_id'] ? 'selected' : '';
                echo "<option value='" . $dir['id'] . "' $selected>" . $dir['name'] . "</option>";
            }
        }
        ?>
    </select><br>
    Actors: 
    <select name="actor_ids[]" multiple required>
        <?php
        if ($actors->num_rows > 0) {
            while($actor = $actors->fetch_assoc()) {
                $selected = in_array($actor['id'], $selected_actor_ids) ? 'selected' : '';
                echo "<option value='" . $actor['id'] . "' $selected>" . $actor['name'] . "</option>";
            }
        }
        ?>
    </select><br>
    <input type="submit" value="Update">
</form>

</body>
</html>
<?php
$conn->close();
?>
