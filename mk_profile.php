<?php
// Start the session
session_start();

// Include your database connection file
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch the current user's data using the username
$sql = "SELECT * FROM user_auth WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // Bind the username (string)
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user data is fetched correctly
if (!$user) {
    echo "Error: User data not found.";
    exit();
}

// Fetch the existing photo album from the database
$existingPhotoAlbum = array_filter(explode(',', $user['photo_album']));

// Initialize message variables
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_photo'])) {
        // Handle deletion of a photo
        $photoToDelete = $_POST['delete_photo'];
        if (($key = array_search($photoToDelete, $existingPhotoAlbum)) !== false) {
            unset($existingPhotoAlbum[$key]);
            // Delete the photo file from the server
            if (file_exists($photoToDelete)) {
                unlink($photoToDelete);
            }
            // Update the database
            $photo_album = implode(',', $existingPhotoAlbum);
            $updatePhotoAlbumSql = "UPDATE user_auth SET photo_album = ? WHERE username = ?";
            $stmt = $conn->prepare($updatePhotoAlbumSql);
            $stmt->bind_param("ss", $photo_album, $username);
            if ($stmt->execute()) {
                $success_message = "<div class='alert alert-success'>Photo deleted successfully</div>";
            } else {
                $error_message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
        }
    } else {
        // Handle form updates and file uploads
        $newUsername = $_POST['username'];
        $email = $_POST['email'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $eye_col = $_POST['eye_col'];
        $note = $_POST['note'];
        $insta = $_POST['insta'];
        $insta_followers = $_POST['insta_followers'];
        $snap = $_POST['snap'];
        $fb = $_POST['fb'];
        $yt = $_POST['yt'];
        $linkd = $_POST['linkd'];
        $state = $_POST['state']; // Get the state from the form

        // Update the database
        $sql = "UPDATE user_auth SET username = ?, email = ?, height = ?, weight = ?, eye_col = ?, note = ?, insta = ?, insta_followers = ?, snap = ?, fb = ?, yt = ?, linkd = ?, state = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddssssssssss", $newUsername, $email, $height, $weight, $eye_col, $note, $insta, $insta_followers, $snap, $fb, $yt, $linkd, $state, $username);

        if ($stmt->execute()) {
            $success_message = "<div class='alert alert-success'>Record updated successfully</div>";
            // Update the session username in case it was changed
            $_SESSION['username'] = $newUsername;
        } else {
            $error_message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        // Handle file uploads
        if (!empty($_FILES['photo_album']['name'][0])) {
            $files = $_FILES['photo_album'];
            $uploadedPaths = [];

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            foreach ($files['name'] as $position => $file_name) {
                $file_tmp = $files['tmp_name'][$position];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if (in_array($file_ext, $allowed)) {
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;

                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $uploadedPaths[] = $file_destination;
                    } else {
                        echo "<div class='alert alert-danger'>Failed to upload file {$file_name}</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>File extension '{$file_ext}' is not allowed for file {$file_name}</div>";
                }
            }

            // Combine existing and newly uploaded photos
            $combinedPhotoAlbum = array_merge($existingPhotoAlbum, $uploadedPaths);

            // Update the photo_album column in the user_auth table
            $photo_album = implode(',', $combinedPhotoAlbum);

            $updatePhotoAlbumSql = "UPDATE user_auth SET photo_album = ? WHERE username = ?";
            $stmt = $conn->prepare($updatePhotoAlbumSql);
            $stmt->bind_param("ss", $photo_album, $username);
            $stmt->execute();
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
    <title>Update your profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="navbar.css">
    <style>
        html,body {
            background: #a09a84 !important;
            width: 100%;
           
            padding: 0;
            margin: 0;
        }

        .container {
            font-family: "Roboto Condensed", sans-serif;
            font-optical-sizing: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 80vw;
            margin: 14vh auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .grid-item {
            position: relative;
        }

        .grid-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.7);
            border: none;
            color: white;
            border-radius: 50%;
            cursor: pointer;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
                max-width: 90vw;
            }

            h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 10px;
                max-width: 80vw;
            }

            h2 {
                font-size: 1.2rem;
            }
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
    <div class="container">
        <h1>Update Your Profile</h1>

        <?php
        if ($success_message) {
            echo $success_message;
        }
        if ($error_message) {
            echo $error_message;
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo htmlspecialchars($user['username']); ?>">
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <?php if (!empty($_SESSION['username'])): ?>
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img loading="lazy"src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
                            class="img-thumbnail" style="max-width: 120px; border-radius: 50%;">
                    <?php else: ?>
                        <img loading="lazy"src="media/userIcon.png" alt="Default Profile Picture" class="img-thumbnail"
                            style="max-width: 120px; border-radius: 50%;">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="height">Height (cm)</label>
                <input type="number" class="form-control" id="height" name="height"
                    value="<?php echo htmlspecialchars($user['height']); ?>">
            </div>
            <div class="mb-3">
                <label for="weight">Weight (kg)</label>
                <input type="number" class="form-control" id="weight" name="weight"
                    value="<?php echo htmlspecialchars($user['weight']); ?>">
            </div>
            <div class="mb-3">
                <label for="eye_col">Eye Color</label>
                <input type="text" class="form-control" id="eye_col" name="eye_col"
                    value="<?php echo htmlspecialchars($user['eye_col']); ?>">
            </div>
            <div class="mb-3">
                <label for="note">Note</label>
                <textarea class="form-control" id="note"
                    name="note"><?php echo htmlspecialchars($user['note']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="insta">Instagram</label>
                <input type="text" class="form-control" id="insta" name="insta"
                    value="<?php echo htmlspecialchars($user['insta']); ?>">
            </div>
            <div class="mb-3">
                <label for="insta_followers">Instagram Followers</label>
                <input type="number" class="form-control" id="insta_followers" name="insta_followers"
                    value="<?php echo htmlspecialchars($user['insta_followers']); ?>">
            </div>
            <div class="mb-3">
                <label for="snap">Snapchat</label>
                <input type="text" class="form-control" id="snap" name="snap"
                    value="<?php echo htmlspecialchars($user['snap']); ?>">
            </div>
            <div class="mb-3">
                <label for="fb">Facebook</label>
                <input type="text" class="form-control" id="fb" name="fb"
                    value="<?php echo htmlspecialchars($user['fb']); ?>">
            </div>
            <div class="mb-3">
                <label for="yt">YouTube</label>
                <input type="text" class="form-control" id="yt" name="yt"
                    value="<?php echo htmlspecialchars($user['yt']); ?>">
            </div>
            <div class="mb-3">
                <label for="linkd">LinkedIn</label>
                <input type="text" class="form-control" id="linkd" name="linkd"
                    value="<?php echo htmlspecialchars($user['linkd']); ?>">
            </div>
            <div class="mb-3">
                <label for="state">State</label>
                <input type="text" class="form-control" id="state" name="state"
                    value="<?php echo htmlspecialchars($user['state']); ?>">
            </div>
            <div class="mb-3">
                <label for="photo_album">Photo Album(Enter only 5 photos at a time)</label>
                <input type="file" class="form-control" id="photo_album" placeholder="Enter only 10 photos at a time"
                    name="photo_album[]" multiple>
            </div>

            <div class="mb-3">
                <label class="form-label">Existing Photos</label>
                <div class="grid-container">
                    <?php foreach ($existingPhotoAlbum as $photo): ?>
                        <div class="grid-item">
                            <img loading="lazy"src="<?php echo htmlspecialchars($photo); ?>" alt="User Photo">
                            <button type="submit" name="delete_photo" value="<?php echo htmlspecialchars($photo); ?>"
                                class="delete-button">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pDFmDbYWpZTdkE5HHQQMUb6EfSCAozgBq8cwrVbOS4xRr7vZCHDd8J9Hp8KfHTpG"
        crossorigin="anonymous"></script>
</body>

</html>