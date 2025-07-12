<?php
session_start();
include('php/db.php'); // Make sure this path is correct (based on your folder structure)

// Determine if the user is signed in for JavaScript
$is_signed_in = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Genre - LOFO Cinema</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="css/genre.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="index.php">LOFO Cinema</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      <?php
      if (isset($_SESSION['user_id'])) {
          echo '<li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>';
          echo '<li class="nav-item"><a class="nav-link" href="php/logout.php">Logout</a></li>';
      } else {
          echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
      }
      ?>
      <li class="nav-item"><a class="nav-link" href="genre.php">Genre</a></li>
      <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
      <li class="nav-item"><a class="nav-link" href="about.php">About</a></li> <!-- FIXED: removed duplicate <a> -->
    </ul>
  </div>
</nav>

<div class="content">
  <h2>Genres</h2>
  <div class="genres">
    <?php
    $sql = "SELECT DISTINCT genre FROM movies";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='genre'>";
            echo "<h3>" . htmlspecialchars($row['genre']) . "</h3>";

            $genre = $row['genre'];
            $sql_movies = "SELECT id, title, poster FROM movies WHERE genre = ?";
            $stmt_movies = $conn->prepare($sql_movies);
            $stmt_movies->bind_param("s", $genre);
            $stmt_movies->execute();
            $result_movies = $stmt_movies->get_result();

            if ($result_movies->num_rows > 0) {
                echo "<div class='movies'>";
                while ($movie = $result_movies->fetch_assoc()) {
                    echo "<div class='movie'>";
                    echo "<img src='" . htmlspecialchars($movie['poster']) . "' alt='" . htmlspecialchars($movie['title']) . "'>";
                    echo "<p>" . htmlspecialchars($movie['title']) . "</p>";
                    echo "<button class='book-ticket' onclick=\"bookTickets(" . $movie['id'] . ")\">Book Tickets</button>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No movies found in this genre.</p>";
            }
            $stmt_movies->close();
            echo "</div>";
        }
    } else {
        echo "<p>No genres found.</p>";
    }

    $conn->close();
    ?>
  </div>
</div>

<script>
  var isSignedIn = <?php echo $is_signed_in; ?>;
  function bookTickets(movieId) {
    if (isSignedIn === true || isSignedIn === 'true') {
      window.location.href = 'book_tickets.php?movie_id=' + movieId;
    } else {
      alert("You must be signed in to book tickets.");
    }
  }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
