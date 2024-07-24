<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $logo_path = null;

    if (isset($_POST['brand_name'])) {
        // Signup
        $brand_name = $_POST['brand_name'];

        // Handle logo upload
        if (!empty($_FILES['logo']['name'])) {
            $target_dir = "b_logo/";
            $target_file = $target_dir . basename($_FILES["logo"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is an actual image or fake image
            $check = getimagesize($_FILES["logo"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["logo"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // If everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                    $logo_path = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM brand_auth WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Email already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO brand_auth (brand_name, email, password_hash, logo_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $brand_name, $email, $password_hash, $logo_path);
            if ($stmt->execute()) {
                echo "Signup successful.";
            } else {
                echo "Signup failed.";
            }
        }
        $stmt->close();
    } else {
        // Login
        $stmt = $conn->prepare("SELECT id, password_hash, logo_path FROM brand_auth WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $hash, $logo_path);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            echo "Login successful. <br>";
            if ($logo_path) {
                echo "<img src='$logo_path' alt='Brand Logo' style='max-width: 100px;'><br>";
            }
        } else {
            echo "Invalid email or password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Auth</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .container h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .toggle-link {
            text-align: center;
            margin-top: 10px;
        }

        .toggle-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .toggle-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 id="form-title">Login</h2>
        <form id="auth-form" action="brand_auth.php" method="POST" enctype="multipart/form-data">
            <div class="form-group" id="brand-name-group" style="display: none;">
                <label for="brand_name">Brand Name</label>
                <input type="text" id="brand_name" name="brand_name" required>
            </div>
            <div class="form-group" id="logo-group" style="display: none;">
                <label for="logo">Brand Logo</label>
                <input type="file" id="logo" name="logo">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" id="form-button">Login</button>
            </div>
            <div class="toggle-link">
                <span id="toggle-text">Don't have an account? <a href="#" id="toggle-link">Sign up</a></span>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('toggle-link').addEventListener('click', function (event) {
            event.preventDefault();
            const isLogin = document.getElementById('form-title').textContent === 'Login';
            document.getElementById('form-title').textContent = isLogin ? 'Sign Up' : 'Login';
            document.getElementById('form-button').textContent = isLogin ? 'Sign Up' : 'Login';
            document.getElementById('brand-name-group').style.display = isLogin ? 'block' : 'none';
            document.getElementById('logo-group').style.display = isLogin ? 'block' : 'none';
            document.getElementById('toggle-text').innerHTML = isLogin ? 'Already have an account? <a href="#" id="toggle-link">Login</a>' : 'Don\'t have an account? <a href="#" id="toggle-link">Sign up</a>';
        });
    </script>
</body>

</html>