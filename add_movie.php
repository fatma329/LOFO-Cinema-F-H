<?php
session_start();
include('php/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $poster = $_POST['poster'];

    $stmt = $conn->prepare("INSERT INTO movies (title, genre, release_date, poster) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $genre, $release_date, $poster);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>➕ Add New Movie</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Genre</label>
            <input type="text" name="genre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Release Date</label>
            <input type="date" name="release_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Poster URL</label>
            <input type="text" name="poster" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Add Movie</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
