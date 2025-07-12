<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
include('php/db.php');

// Fetch recent bookings
$user_id = $_SESSION['user_id'];
$recent_bookings_sql = "
    SELECT movies.title, showtimes.show_time, tickets.seat_id, tickets.price 
    FROM bookings 
    JOIN tickets ON bookings.ticket_id = tickets.id 
    JOIN showtimes ON tickets.showtime_id = showtimes.id 
    JOIN movies ON showtimes.movie_id = movies.id 
    WHERE bookings.user_id = $user_id 
    ORDER BY bookings.booking_time DESC
    LIMIT 5";
$recent_bookings_result = $conn->query($recent_bookings_sql);

// Fetch past bookings (excluding recent bookings)
$past_bookings_sql = "
    SELECT movies.title, showtimes.show_time, tickets.seat_id, tickets.price 
    FROM bookings 
    JOIN tickets ON bookings.ticket_id = tickets.id 
    JOIN showtimes ON tickets.showtime_id = showtimes.id 
    JOIN movies ON showtimes.movie_id = movies.id 
    WHERE bookings.user_id = $user_id 
    ORDER BY bookings.booking_time DESC
    LIMIT 5 OFFSET 5";
$past_bookings_result = $conn->query($past_bookings_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - LOFO Cinema</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
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
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="php/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="genre.php">Genre</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="profile-container">
            <h1 class="mb-4">Profile</h1>
            <div class="profile-info mb-4">
                <p><strong>First Name:</strong> <?php echo isset($_SESSION['firstname']) ? $_SESSION['firstname'] : 'Not provided'; ?></p>
                <p><strong>Last Name:</strong> <?php echo isset($_SESSION['lastname']) ? $_SESSION['lastname'] : 'Not provided'; ?></p>
                <p><strong>Email:</strong> <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Not provided'; ?></p>
                <button class="btn btn-primary" onclick="showEditForm()">Edit Profile</button>
            </div>
            <div class="edit-form" style="display:none;">
                <form action="php/update_profile.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="firstname" value="<?php echo $_SESSION['firstname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="lastname" value="<?php echo $_SESSION['lastname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" value="<?php echo $_SESSION['email']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
            <h2 class="mt-5 text-dark">Recent Bookings</h2>
            <?php if ($recent_bookings_result->num_rows > 0): ?>
                <div class="recent-bookings">
                    <?php while($booking = $recent_bookings_result->fetch_assoc()): ?>
                        <div class="booking">
                            <h3 class="text-dark"><?php echo $booking['title']; ?></h3>
                            <p class="text-dark">Showtime: <?php echo $booking['show_time']; ?></p>
                            <p class="text-dark">Seat: <?php echo $booking['seat_id']; ?></p>
                            <p class="text-dark">Price: <?php echo $booking['price']; ?> L.E</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-dark">No recent bookings found.</p>
            <?php endif; ?>
            <h2 class="mt-5 text-dark">Past Bookings</h2>
            <?php if ($past_bookings_result->num_rows > 0): ?>
                <div class="past-bookings">
                    <?php while($booking = $past_bookings_result->fetch_assoc()): ?>
                        <div class="booking">
                            <h3 class="text-dark"><?php echo $booking['title']; ?></h3>
                            <p class="text-dark">Showtime: <?php echo $booking['show_time']; ?></p>
                            <p class="text-dark">Seat: <?php echo $booking['seat_id']; ?></p>
                            <p class="text-dark">Price: <?php echo $booking['price']; ?>L.E</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-dark">No past bookings found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function showEditForm() {
            document.querySelector('.profile-info').style.display = 'none';
            document.querySelector('.edit-form').style.display = 'block';
        }
    </script>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
