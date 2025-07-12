<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LOFO Cinema</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
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
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="login-container">

            <div class="form-container sign-in-container">
                <form action="php/signin.php" method="POST">
                    <h2>Sign In</h2>

                    <!-- 🔴 Error message shown INSIDE the form -->
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo "<div style='color: red; font-weight: bold; font-size: 14px; margin-bottom: 10px; text-align: center;'>" . $_SESSION['error'] . "</div>";
                        unset($_SESSION['error']);
                    }
                    ?>

                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Sign In</button>
                </form>
            </div>

            <div class="form-container sign-up-container">
                <form action="php/signup.php" method="POST">
                    <h2>Sign Up</h2>
                    <input type="text" name="firstname" placeholder="First Name" required>
                    <input type="text" name="lastname" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Sign Up</button>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h2>Welcome Back!</h2>
                        <p>To keep connected with us please login with your personal info</p>
                        <button class="ghost" id="signIn">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h2>Hello, Friend!</h2>
                        <p>Enter your personal details and start your journey with us</p>
                        <button class="ghost" id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ Signup success or failure message (optional) -->
        <?php
        if (isset($_SESSION['message'])) {
            $msg_type = $_SESSION['msg_type'];
            $message = $_SESSION['message'];
            echo "<div class='custom-alert alert-$msg_type'>$message</div>";
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        }
        ?>
    </div>

    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
