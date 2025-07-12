<?php
session_start();
include('php/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $poster = $_POST['poster'];

    $stmt = $conn->prepare("UPDATE movies SET title=?, genre=?, release_date=?, poster=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $genre, $release_date, $poster, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Movie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>✏️ Edit Movie</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $movie['title']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Genre</label>
            <input type="text" name="genre" value="<?php echo $movie['genre']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Release Date</label>
            <input type="date" name="release_date" value="<?php echo $movie['release_date']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Poster URL</label>
            <input type="text" name="poster" value="<?php echo $movie['poster']; ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning">Update Movie</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
