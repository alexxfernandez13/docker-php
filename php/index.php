<?php
require_once "config.php";
session_start();

// Redirect if the user is already logged in
if (isset($_SESSION["username"])) {
    header("location:home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Login
    if (isset($_POST["login"])) { 
        $username = mysqli_real_escape_string($connection, $_POST["username"]);
        $password = mysqli_real_escape_string($connection, $_POST["password"]);

        // Query to get the user by username
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Check if the password matches
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                header("location:home.php");
                exit;
            } else {
                $error = "Incorrect password";
            }
        } else {
            $error = "User not found";
        }
    }

    // Register
    if (isset($_POST["register"])) { 
        $username = mysqli_real_escape_string($connection, $_POST["username"]);
        $password = mysqli_real_escape_string($connection, $_POST["password"]);
        $repeat_password = mysqli_real_escape_string($connection, $_POST["repeat_password"]);

        // Validate empty fields
        if (empty($username) || empty($password) || empty($repeat_password)) {
            $error = "All fields are mandatory!";
        } else {
            // Check if passwords match
            if ($password !== $repeat_password) {
                $error = "Passwords do not match";
            } else {
                // Check if the username already exists
                $query = "SELECT * FROM users WHERE username = '$username'";
                $result = mysqli_query($connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    $error = "Username already exists";
                } else {
                    // Insert new user into the database
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

                    if (mysqli_query($connection, $query)) {
                        header("location:index.php");
                        exit;
                    } else {
                        $error = "Registration error";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Login & Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container align-middle mt-5">
        <?php if (isset($_GET["action"]) && $_GET["action"] == "register") { ?>
            <form method="post" action="">
                <h3 class="text-center">Register</h3>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>

                <div class="form-outline mb-4">
                    <input type="text" id="username" name="username" class="form-control" required />
                    <label class="form-label" for="username">Username</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control" required />
                    <label class="form-label" for="password">Password</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="password" id="repeat_password" name="repeat_password" class="form-control" required />
                    <label class="form-label" for="repeat_password">Repeat Password</label>
                </div>

                <input type="submit" class="btn btn-primary btn-block mb-4 w-100" value="Register" name="register" />
                <div class="text-center">
                    <p>Already have an account? <a href="index.php">Login</a></p>
                </div>
            </form>
        <?php } else { ?>
            <form method="post" action="">
                <h3 class="text-center">Login</h3>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>

                <div class="form-outline mb-4">
                    <input type="text" id="username" name="username" class="form-control" required />
                    <label class="form-label" for="username">Username</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control" required />
                    <label class="form-label" for="password">Password</label>
                </div>

                <input type="submit" class="btn btn-primary btn-block mb-4 w-100" value="Login" name="login" />
                <div class="text-center">
                    <p>Don't have an account? <a href="index.php?action=register">Register</a></p>
                </div>
            </form>
        <?php } ?>
    </div>
</body>
</html>