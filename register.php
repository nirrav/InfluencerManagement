<?php
include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_picture = $_FILES['profile_picture'];

    // Validate input fields
    if ($password != $confirm_password) {
        $message = '<div class="alert alert-danger" role="alert">Passwords do not match.</div>';
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM user_auth WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = '<div class="alert alert-danger" role="alert">Username or email already exists.</div>';
        } else {
            $stmt->close();

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Handle profile picture upload
            $target_dir = "dp_pics/";
            $target_file = $target_dir . basename($profile_picture["name"]);
            $upload_ok = 1;
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if file is an actual image
            $check = getimagesize($profile_picture["tmp_name"]);
            if ($check === false) {
                $message = '<div class="alert alert-danger" role="alert">File is not an image.</div>';
                $upload_ok = 0;
            }

            // Check file size (set to 5 MB)
            if ($profile_picture["size"] > 50000000) {
                $message = '<div class="alert alert-danger" role="alert">Sorry, your file is too large.</div>';
                $upload_ok = 0;
            }

            // Allow certain file formats
            $allowed_formats = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($image_file_type, $allowed_formats)) {
                $message = '<div class="alert alert-danger" role="alert">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
                $upload_ok = 0;
            }

            // Check if $upload_ok is set to 0 by an error
            if ($upload_ok == 0) {
                $message = '<div class="alert alert-danger" role="alert">Sorry, your file was not uploaded.</div>';
            } else {
                if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
                    // Insert user into database with profile picture path
                    $stmt = $conn->prepare("INSERT INTO user_auth (username, email, password, profile_picture) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $email, $hashed_password, $target_file);

                    if ($stmt->execute() === TRUE) {
                        $message = '<div class="alert alert-success" role="alert">Registration successful. Redirecting to login page...</div>';
                        // Redirect to login page after successful registration
                        header("refresh:3;url=login.php");
                    } else {
                        $message = '<div class="alert alert-danger" role="alert">Error: ' . $stmt->error . '</div>';
                    }

                    $stmt->close();
                } else {
                    $message = '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your file.</div>';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="media/bioscope-icon.png">
    <title>Register yourself</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="navbar.css">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            font-optical-sizing: auto;
            background: #a09a84 !important;
            margin-top: 15vh;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-dark navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="media/bioscope-icon.png" alt="Logo" width="35" height="auto"
                    class="d-inline-block align-text-top">
                <u>Bioscope Media</u>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <div class="d-flex w-100 justify-content-center">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <div class="nav-btn">
                                <button type="button" onclick="window.location.href='artist.php'">Artists</button>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-btn">
                                <button type="button" onclick="window.location.href='model.php'">Brands</button>
                            </div>
                        </li>
                    </ul>
                </div>
                <button class="btn btn-outline-light" onclick="goBack()">Back</button>
                <script>
                    function goBack() {
                        window.history.back();
                    }
                </script>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2><u>Sign Up</u></h2>
        <?php echo $message; ?>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*"
                    required>
            </div>
            <button type="submit" class="btn" style="background: #064439; color: white;">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php" style="color: black;">Login here</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0    T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>

</html>