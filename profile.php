<?php
// Start the session
session_start();

// Include your database connection file
include 'config.php';

// Check if the user ID is set in the GET request
if (!isset($_GET['user_id'])) {
    echo "Error: User ID not provided.";
    exit();
}

$user_id = $_GET['user_id'];

// Fetch the user's data using the user ID
$sql = "SELECT * FROM user_auth WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user ID (integer)
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user data is fetched correctly
if (!$user) {
    echo "Error: User data not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="media/bioscope-icon.png">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@100;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMPXo1hN4zDzLZQoh0kPlR3/x8ABk9h5HFLfl6" crossorigin="anonymous">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="navbar.css">
    <style>
        body {
            background: #a09a84 !important;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-top: 60px;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        .container {
            font-family: "Roboto Condensed", sans-serif;
            background: whitesmoke;
            color: black;
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            margin: 50px auto;
            max-width: 1300px;
        }

        .profile-header {
            display: flex;
            text-align: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .profile-header h1 {
            font-weight: 300;
            font-size: 3.5rem;
            margin: 0;
        }

        .profile-header h3 {
            font-weight: 500;
            font-size: 2.5rem;
            margin: 0;
        }

        .profile-picture {
            border-radius: 50%;
            width: 250px;
            height: auto;
            object-fit: cover;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }

        .profile-picture:hover {
            transform: scale(1.05);
        }

        .swiper {
            width: 95%;
            height: 700px;
            margin-bottom: 30px;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;

        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .grid-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            object-fit: cover;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .grid-item img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: center;
            }

            .profile-picture {
                width: 150px;
                height: 150px;
            }

            .profile-header h1 {
                font-size: 2rem;
            }

            .profile-header h3 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            .profile-picture {
                width: 120px;
                height: 120px;
            }

            .profile-header h1 {
                font-size: 1.8rem;
            }

            .profile-header h3 {
                font-size: 1rem;
            }
        }

        .profile-details p {
            font-size: 1.2rem;
        }

        .profile-details strong {
            font-size: 1.3rem;
        }

        .social-links a {
            font-size: 1.2rem;
        }

        .prText {
            color: black;
            margin: auto;
            padding-top: 5vh;
            text-align: center;
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
    <h2 class="prText" style=""><strong><u><?php echo htmlspecialchars($user['username']); ?>'s Profile</u></strong>
    </h2>
    <div class="container">
        <div class="profile-header">
            <img loading="lazy" src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
                class="profile-picture"
                onclick="openModal('<?php echo htmlspecialchars($user['profile_picture']); ?>')">
            <div>
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <h3><?php echo htmlspecialchars($user['email']); ?></h3>
            </div>
        </div>
        <div class="row profile-details">
            <div class="col-md-6">
                <p><strong>Location: <?php echo htmlspecialchars($user['state']); ?></strong></p>
                <p><strong>Height: <?php echo htmlspecialchars($user['height']); ?> cm</strong></p>
                <p><strong>Weight: <?php echo htmlspecialchars($user['weight']); ?> kg</strong></p>
                <p><strong>Eye Color: <?php echo htmlspecialchars($user['eye_col']); ?></strong></p>
                <p><strong>Note: <?php echo htmlspecialchars($user['note']); ?></strong></p>
            </div>
            <div class="col-md-6 social-links">
                <?php if (!empty($user['insta_followers'])): ?>
                    <p><strong>Instagram Followers:<span
                                class="text-blank"><?php echo htmlspecialchars($user['insta_followers']); ?></span> <i
                                class="fab fa-instagram"></i></strong> </p>
                <?php endif; ?>

                <?php if (!empty($user['insta'])): ?>
                    <p><strong>Instagram:</strong> <a href="<?php echo htmlspecialchars($user['insta']); ?>" target="_blank"
                            class="text-black"><i class="fab fa-instagram"></i>
                            <?php echo htmlspecialchars($user['insta']); ?></a></p>
                <?php endif; ?>
                <?php if (!empty($user['snap'])): ?>
                    <p><strong>Snapchat:</strong> <a href="<?php echo htmlspecialchars($user['snap']); ?>" target="_blank"
                            class="text-black"><i class="fab fa-snapchat-ghost"></i>
                            <?php echo htmlspecialchars($user['snap']); ?></a></p>
                <?php endif; ?>
                <?php if (!empty($user['fb'])): ?>
                    <p><strong>Facebook:</strong> <a href="<?php echo htmlspecialchars($user['fb']); ?>" target="_blank"
                            class="text-black"><i class="fab fa-facebook"></i>
                            <?php echo htmlspecialchars($user['fb']); ?></a></p>
                <?php endif; ?>
                <?php if (!empty($user['yt'])): ?>
                    <p><strong>YouTube:</strong> <a href="<?php echo htmlspecialchars($user['yt']); ?>" target="_blank"
                            class="text-black"><i class="fab fa-youtube"></i>
                            <?php echo htmlspecialchars($user['yt']); ?></a></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php
                $photos = explode(',', $user['photo_album']);
                $total_photos = count($photos);
                for ($i = 0; $i < min(7, $total_photos); $i++) {
                    $photoPath = trim($photos[$i]);
                    echo '<div class="swiper-slide"><img loading="lazy"src="' . htmlspecialchars($photoPath) . '"  alt="Photo"></div>';
                }
                ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="grid-container">
            <?php
            for ($i = 7; $i < $total_photos; $i++) {
                $photoPath = trim($photos[$i]);
                echo '<div class="grid-item"><img loading="lazy"src="' . htmlspecialchars($photoPath) . '"   alt="Photo"></div>';
            }
            ?>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybBogGzUglzGL5lJ7B1kk3WpP180xCFpDIpCjmFwpOMlWR6pG" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-QFQtQ7N4w7G+U6q7ib8yQBC0K5N4liNP0SU7oyXt0mOOvyNkgjQRVvoxMfooRMhE" crossorigin="anonymous">
        </script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        function openModal(imageSrc) {
            var modal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('modalImage').src = imageSrc;
            modal.show();
        }
    </script>


    <!-- Modal for image zoom -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img loading="lazy" id="modalImage" src="" alt="Full size image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</body>

</html>