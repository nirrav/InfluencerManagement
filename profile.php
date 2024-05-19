<?php
// Database connection
$servername = "localhost";
$username = "root"; // Assuming username is root
$password = ""; // No password
$dbname = "website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Function to sanitize inputs
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = sanitize_input($_POST['name']);
    $dob = sanitize_input($_POST['dob']);
    $gender = sanitize_input($_POST['gender']);
    $email = sanitize_input($_POST['email']);
    $height = sanitize_input($_POST['height']);
    $weight = sanitize_input($_POST['weight']);
    $eye_col = sanitize_input($_POST['eye_col']);

    // Prepare SQL statement based on the form submitted
    if (isset($_POST['submit_model'])) {
        $stmt = $conn->prepare("INSERT INTO model (user_id, name, dob, gender, email, height, weight, eye_col) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    } elseif (isset($_POST['submit_influencer'])) {
        $stmt = $conn->prepare("INSERT INTO influencer (user_id, name, dob, gender, email, height, weight, eye_col) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    }

    // Get user ID from session or wherever it's stored
    $user_id = 1; // Replace with the actual user ID

    // Bind parameters and execute statement
    if ($stmt) {
        $stmt->bind_param("isssdsss", $user_id, $name, $dob, $gender, $email, $height, $weight, $eye_col);
        if ($stmt->execute()) {
            // Redirect to the same page with success message
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        } else {
            // Check if the error is due to a duplicate entry
            if ($conn->errno == 1062) { // Duplicate entry error code
                $message = "<p style='color: red;'>Error: Email already exists</p>";
            } else {
                $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    } else {
        $message = "<p style='color: red;'>Error: Unable to prepare statement</p>";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="media/bioscope-icon.png">
    <title>Registration Form</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 150px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 80vw;
            max-width: 100%;
            min-height: 90vh;
            margin-top: 10px;
        }

        .container p {
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span {
            font-size: 12px;
        }

        .container a {
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
        }

        .container button {
            background-color: rgb(105, 105, 105);
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 2.5px solid black;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .container button.hidden {
            background-color: transparent;
            border-color: #fff;
        }

        .container form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .sign-in {
            transform: translateX(100%);
        }

        .sign-up {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.active .sign-up {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        @keyframes move {

            0%,
            49.99% {
                opacity: 0;
                z-index: 1;
            }

            50%,
            100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .social-icons {
            margin: 20px 0;
        }

        .social-icons a {
            border: 1px solid #ccc;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 40px;
            height: 40px;
        }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 150px 150px 150px;
            z-index: 1000;
        }

        .container.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 150px 150px 150px 150px;
        }

        .toggle {
            height: 100%;
            background: linear-gradient(to right, #000000, #4d4e4d);
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .container.active .toggle {
            transform: translateX(50%);
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .toggle-left {
            transform: translateX(-200%);
        }

        .container.active .toggle-left {
            transform: translateX(0);
        }

        .toggle-right {
            right: 0;
            transform: translateX(0);
        }

        .container.active .toggle-right {
            transform: translateX(200%);
        }

        .navbar-container {
            width: 100%;
        }
    </style>
</head>

<body>
    
    <?php
    if (!empty($message)) {
        echo $message;
    } elseif (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p style='color: green;'>Model Registered successfully</p>";
    }
    ?>
    <div class="container" id="container">
        <div class="form-container sign-up">

            <form method="POST">
                <h1>Influencer Registration</h1>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="height">Height (Feet):</label>
                <input type="number" id="height" name="height" step="0.01" required>
                <label for="weight">Weight (Kg):</label>
                <input type="number" id="weight" name="weight" step="0.01" required>
                <label for="eye_col">Eye Color:</label>
                <input type="text" id="eye_col" name="eye_col" required>
                <button type="submit" name="submit_influencer">Submit</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="POST">
                <h1>Model Registration</h1>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="height">Height (Feet):</label>
                <input type="number" id="height" name="height" step="0.01" required>
                <label for="weight">Weight (Kg):</label>
                <input type="number" id="weight" name="weight" step="0.01" required>
                <label for="eye_col">Eye Color:</label>
                <input type="text" id="eye_col" name="eye_col" required>
                <button type="submit" name="submit_model">Submit</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Are you a model?</h1>
                    <p>Register as a model...</p>
                    <button class="hidden" id="login">Register</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Are you an influencer?</h1>
                    <p>Register as an influencer...</p>
                    <button class="hidden" id="register">Register</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>