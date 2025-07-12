<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - LOFO Cinema</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
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
        <h2>Contact Us</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Contact Information</h3>
                <p><strong>Hotline:</strong> 19031</p>
                <p><strong>Email:</strong> LOFO@cinema.eg</p>
                <p><strong>Address:</strong> Smart Village</p>
            </div>
            <form id="contactForm" class="contact-form">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea class="form-control" id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
            <div id="formMessage" class="mt-3"></div>
        </div>
    </div>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'php/contact_form.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#formMessage').html('<div class="alert alert-success">Message sent successfully!</div>');
                        $('#contactForm')[0].reset();
                    },
                    error: function() {
                        $('#formMessage').html('<div class="alert alert-danger">There was an error sending your message. Please try again later.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
