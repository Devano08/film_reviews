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
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- background -->
    <img class="absolute z-[-1]" style="width: 100%; height:100%" src="../image/background.jpeg" alt="">

<!-- Header -->
<div class="font-semibold w-full h-20 bg-gray-800 flex items-center justify-around text-white">
    <p>Ferdinand Lauren</p>
    <p>Devano Michael</p>
    <p>Rizky Arrasyid</p>
</div>

<!-- Main Content -->
<div class="w-full h-full flex justify-center items-center mt-[90px]">
    <!-- Buttons -->
    <div class="bg-white w-full h-[430px] max-w-md p-8 shadow-lg rounded-lg mx-4 flex flex-col items-center">
    <h2 class="text-2xl font-bold mb-6 text-center">Film Reviews by Kelompok 3</h2>
    <!-- Container Tombol -->
    <div class="w-full h-auto flex flex-col mt-16 ">
        <a href="../Review/index.php" class="w-full mb-2 text-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Review</a>
        <a href="../Kategori/index.php" class="w-full mb-2 text-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Manage Categories</a>
        <a href="../Actors/index.php" class="w-full mb-2 text-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Manage Actors</a>
        <a href="../Sutradara/index.php" class="w-full text-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Manage Directors</a>
    </div>
    </div>
</div>

</body>
</html>
<?php
$conn->close();
?>
