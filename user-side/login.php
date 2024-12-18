<?php
session_start();
include("db.php");

// Process Login
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM form WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $stored_hashed_password = $user_data['password'];

            if (password_verify($password, $stored_hashed_password)) {
                $_SESSION['user_data'] = $user_data['id'];
                header("Location: index.php");
                exit();
            } else {
                echo "<script type='text/javascript'>alert('Invalid password.');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('No user found with this email.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script type='text/javascript'>alert('Please enter valid credentials.');</script>";
    }
}

// Process Signup
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script type='text/javascript'>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        if (!empty($email) && !empty($password) && !is_numeric($email)) {
            $stmt = $conn->prepare("INSERT INTO form (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: landingpage.html");
                exit();
            } else {
                echo "<script type='text/javascript'>alert('Error: " . $conn->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script type='text/javascript'>alert('Please enter valid information');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - Puerto Princesa Traveloca</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card">
                    <div class="card-header text-center">
                        <ul class="nav nav-tabs card-header-tabs" id="auth-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab"
                                    aria-controls="login" aria-selected="true">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="signup-tab" data-toggle="tab" href="#signup" role="tab"
                                    aria-controls="signup" aria-selected="false">Register</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="auth-tabs-content">
                            <!-- Login Form -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <h3>Login</h3>
                                <form method="POST" action="login.php">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                                </form>
                                <div class="register-link mt-3">
                                    Don’t have an account? <a href="#signup" data-toggle="tab">Register Here</a>
                                </div>
                            </div>

                            <!-- Signup Form -->
                            <div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                                <h3>Register</h3>
                                <form method="POST" action="login.php">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" required>
                                        <!-- Error message will appear here -->
                                        <span id="password-error"></span>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block" name="signup">Register</button>
                                </form>
                                <p class="mt-3">Already have an account? <a href="#login" data-toggle="tab">Login Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="js/login.js" defer></script>
    <script src="js/scripts.js" defer></script>
</body>

</html>
