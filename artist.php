<?php
// Start the session
session_start();

// Include your database connection file
include 'config.php';

// Check if the user is logged in
$user_logged_in = isset($_SESSION['username']);

// Fetch data from the database
$sql = "SELECT id, username, profile_picture FROM user_auth"; // Use the correct column name for the primary key
$result = $conn->query($sql);

// Close database connection
$conn->close();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="png" href="media/bioscope-icon.png">
    <title>Artists- Bioscope Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css">
    <style>
        /* General Styling */

        body {
            background: #a09a84 !important;
            color: #fff;
            width: 100%;
            font-family: "Roboto Condensed", sans-serif;
        }


        body::-webkit-scrollbar {
            display: none;
        }

        /* artist page */


        .box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
            padding: 32px;
        }

        .model-card {
            position: relative;
            width: 300px;
            height: 400px;
            background: #ffffff;
            border-radius: 3px;
            margin: 0 auto;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .2);
        }

        .model-card::before,
        .model-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 3px;
            background: #ffffff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .2);
            z-index: -1;
            transition: .5s;
        }

        .model-card:hover::after {
            transform: rotate(10deg);
        }

        .mode-card:hover::before {
            transform: rotate(20deg);
        }

        .model-card .imgBox {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: #101010;
            z-index: 1;
            transition: .5s;
        }

        .model-card:hover .imgBox {
            bottom: 75px;
        }

        .model-card .imgBox img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .model-card .details {
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 10px;
            height: 55px;
        }

        .model-card .details h2 {
            font-family: "Roboto Condensed", sans-serif;
            font-optical-sizing: auto;
            margin-top: 5px;
            padding: 0;
            font-weight: 900;
            font-size: 20px;
            color: #101010;
            text-align: center;
            line-height: 1.15em;
        }

        .button {
            font-family: "Roboto Condensed", sans-serif;
            font-optical-sizing: auto;
            background: #a09a84 !important;

        }

        .model-card .details h2 span {
            font-weight: 400;
            font-size: 15px;
            color: #0dac4f;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-dark navbar-dark">
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
    <!-- Your card content -->
    <?php if ($result->num_rows > 0): ?>
        <div class="container">
            <div class="row justify-content-center">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6"> <!-- Adjust column sizes for different screen sizes -->
                        <div class="box">
                            <div class="model-card">
                                <div class="imgBox">
                                    <img loading="lazy" src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="">
                                </div>
                                <div class="details">
                                    <h2><?php echo htmlspecialchars($row["username"]); ?><br><span>
                                            <!-- Replace email with button -->
                                            <form action="profile.php" method="get">
                                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                                <!-- Set button text dynamically -->
                                                <?php if ($user_logged_in): ?>
                                                    <button type="submit" class="btn"
                                                        style="background: #064439; color: white;">View Profile</button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-dark btn-disabled" disabled>Login to
                                                        access profile...</button>
                                                <?php endif; ?>
                                            </form>
                                        </span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>