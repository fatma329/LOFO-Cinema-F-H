<?php
session_start();
include('php/db.php');


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$movies = [];
$sql = "SELECT * FROM movies ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - LOFO Cinema</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>🎬 Admin Dashboard - Manage Movies</h2>
    <a href="add_movie.php" class="btn btn-success mb-3">+ Add New Movie</a>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Poster</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($movies as $movie): ?>
            <tr>
                <td><?php echo $movie['id']; ?></td>
                <td><img src="<?php echo $movie['poster']; ?>" alt="" width="60"></td>
                <td><?php echo $movie['title']; ?></td>
                <td><?php echo $movie['genre']; ?></td>
                <td><?php echo $movie['release_date']; ?></td>
                <td>
                    <a href="edit_movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
