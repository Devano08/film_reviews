<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM categories WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    header('Location: categories.php');
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
