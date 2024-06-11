<?php
include 'db.php';

$id = $_GET['id'];

// Hapus terlebih dahulu entri terkait dari tabel review_actors
$sql_delete_review_actors = "DELETE FROM review_actors WHERE review_id=$id";
if ($conn->query($sql_delete_review_actors) === TRUE) {
    // Setelah entri terkait dihapus, baru hapus review itu sendiri
    $sql_delete_review = "DELETE FROM reviews WHERE id=$id";
    if ($conn->query($sql_delete_review) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error deleting review: " . $conn->error;
    }
} else {
    echo "Error deleting review actors: " . $conn->error;
}

$conn->close();
?>
