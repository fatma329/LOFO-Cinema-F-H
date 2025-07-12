<?php
session_start();
include('php/db.php');  // Include this once at the top

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$is_signed_in = isset($_SESSION['user_id']) ? 'true' : 'false'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOFO Cinema</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">LOFO Cinema</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="php/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="genre.php">Genre</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="index.php" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <div class="content">
        <?php if ($search_query): ?>
            <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
            <div class="movies">
                <?php
                $sql = "SELECT * FROM movies WHERE title LIKE '%$search_query%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='movie'>";
                        echo "<img src='" . $row['poster'] . "' alt='" . $row['title'] . "'>";
                        echo "<p>" . $row['title'] . "</p>";
                        echo "<button class='book-ticket' onclick=\"bookTickets({$row['id']})\">Book Tickets</button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No movies found matching your search.</p>";
                }
                ?>
            </div>
        <?php else: ?>
            <h2>Now Playing</h2>
            <div class="movies">
                <?php
                $sql = "SELECT * FROM movies WHERE release_date <= CURRENT_DATE()";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='movie'>";
                        echo "<img src='" . $row['poster'] . "' alt='" . $row['title'] . "'>";
                        echo "<p>" . $row['title'] . "</p>";
                        echo "<button class='book-ticket' onclick=\"bookTickets({$row['id']})\">Book Tickets</button>";
                        echo "</div>";
                    }
                } else {
                    echo "No movies found.";
                }
            ?>
            </div>
            <h2>Coming Soon</h2>
            <div class="movies">
                <?php
                $sql = "SELECT * FROM movies WHERE release_date > CURRENT_DATE()";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='movie'>";
                        echo "<img src='" . $row['poster'] . "' alt='" . $row['title'] . "'>";
                        echo "<p>" . $row['title'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "No upcoming movies.";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <script>
        var isSignedIn = <?php echo $is_signed_in; ?>;
        
        function bookTickets(movieId) {
            if (isSignedIn) {
                window.location.href = 'book_tickets.php?movie_id=' + movieId;
            } else {
                alert("You must be signed in to book tickets.");
            }
        }
    </script>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
