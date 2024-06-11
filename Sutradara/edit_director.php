<?php
include 'db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $sql = "UPDATE directors SET name='$name' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM directors WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Director</title>
</head>
<body>

<h2>Edit Director</h2>

<form method="POST" action="">
    Name: <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
    <input type="submit" value="Update">
</form>

</body>
</html>
<?php
$conn->close();
?>
