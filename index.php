<?php
// Start the session
session_start();

// Include your database connection file
include 'config.php';

// Initialize variables to store user data
$username = '';
$profile_picture = 'media/userIcon.png'; // Default profile picture

// Check if there's a logged-in user
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);

    // Fetch the profile picture for the logged-in user
    if ($stmt = $conn->prepare("SELECT profile_picture FROM user_auth WHERE username = ?")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query was successful and data exists
        if ($result && $result->num_rows > 0) {
            // Fetch the profile picture from the database result set
            $row = $result->fetch_assoc();
            $profile_picture = htmlspecialchars($row['profile_picture']);
        }
        // Close prepared statement
        $stmt->close();
    } else {
        // Handle query preparation error
        error_log("Failed to prepare statement: " . $conn->error);
    }
}

// Fetch the top 3 influencers with the highest Instagram followers
$top_influencers = [];
if ($stmt = $conn->prepare("SELECT id, username, state, insta_followers, profile_picture FROM user_auth ORDER BY insta_followers DESC LIMIT 3")) {
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful and data exists
    if ($result && $result->num_rows > 0) {
        // Fetch all influencers and store in an array
        while ($row = $result->fetch_assoc()) {
            $top_influencers[] = $row;
        }
    }
    // Close prepared statement
    $stmt->close();
} else {
    // Handle query preparation error
    error_log("Failed to prepare statement: " . $conn->error);
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="media/bioscope-icon.png">
    <title>Bioscope Media - Influencer Management</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css'>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="custom.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmF/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
</head>

<body>
    <div class="preloader">
        <div class="brand-logo">
            <img loading="lazy" src="media/bioscope-logo.png" alt="Brand Logo">
            <h1><u>Bioscope Media</u></h1>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg bg-body-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
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
                <form class="d-flex">
                    <?php if (!empty($_SESSION['username'])): ?>
                        <a class="d-inline-block align-top" style="cursor: pointer;">
                            <?php if (!empty($profile_picture)): ?>
                                <img loading="lazy" src="<?php echo $profile_picture; ?>" alt="Profile" width="45" height="auto"
                                    style="border-radius: 50%;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                    aria-controls="offcanvasRight">
                            <?php else: ?>
                                <img loading="lazy" src="media/userIcon.png" alt="Default Profile" width="auto" height="40"
                                    class="d-inline-block align-top" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                    aria-controls="offcanvasRight">
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <a class="d-inline-block align-top" style="cursor: pointer;">
                            <img loading="lazy" src="media/userIcon.png" alt="Default Profile" width="auto" height="40"
                                class="d-inline-block align-top" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                aria-controls="offcanvasRight">
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </nav>



    <!-- Offcanvas Component -->
    <div class="offcanvas offcanvas-end" style="font-family: Roboto Condensed, sans-serif; font-optical-sizing:
        auto; background-color: #a09a84da;" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">User Profile</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php if (!empty($_SESSION['username'])): ?>
                <?php if (!empty($profile_picture)): ?>
                    <img loading="lazy" src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="img-thumbnail"
                        style="max-width: 120px; border-radius: 50%;">
                <?php endif; ?>
                <h5>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h5>
                <a href="mk_profile.php" class="btn  mt-3" style="background: #064439; color: white;">Edit Profile</a>
                <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
            <?php else: ?>
                <p>Login for further access...</p>
                <?php if (isset($_SESSION['upload_error'])): ?>
                    <p class="text-danger"><?php echo htmlspecialchars($_SESSION['upload_error']); ?></p>
                    <?php unset($_SESSION['upload_error']); ?>
                <?php endif; ?>
                <a class='btn btn-outline-dark glamorous-button' href='login.php'>Login</a>
                <a class='btn btn-outline-dark glamorous-button' href='login.php'>Sign Up</a>
            <?php endif; ?>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
            <div class="slideshow-container1">
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s1.JPG" alt="Slide 1">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s2.JPG" alt="Slide 2">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s3.JPG" alt="Slide 3">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s4.JPG" alt="Slide 4">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s5.JPG" alt="Slide 5">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="slideshow-container2">
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s6.JPG" alt="Slide 6">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s7.JPG" alt="Slide 7">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s8.JPG" alt="Slide 8">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s9.JPG" alt="Slide 9">
                </div>
                <div class="slide" style="display: none; opacity: 0;">
                    <img loading="lazy" src="media/slideshow/s10.JPG" alt="Slide 10">
                </div>
            </div>
        </div>
    </div>
    <section class="our-services">
        <div class="container">
            <h2><u>Our Services</u></h2>
            <div class="row">
                <div class="col-md-4">
                    <h3>Influencer Management</h3>
                    <p>We provide personalized management to help influencers grow their brand and reach their full
                        potential.</p>
                </div>
                <div class="col-md-4">
                    <h3>Brand Collaborations</h3>
                    <p>Connect with top brands and participate in exciting collaboration opportunities to enhance your
                        influence.</p>
                </div>
                <div class="col-md-4">
                    <h3>Content Creation</h3>
                    <p>Get access to professional content creation services to ensure your posts are engaging and
                        high-quality.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="card-container">
        <div class="card">
            <div class="circle">
                <h2><u>Premium Brands</u></h2>
            </div>
            <div class="content">
                <p>Explore collaborations with top-tier brands and elevate your influence to new heights!</p>
                <!-- <a href="#">Read More</a> -->
            </div>
        </div>
        <div class="card">
            <div class="circle">
                <h2><u>Visit our studio</u></h2>
            </div>
            <div class="content">
                <p>Embark on a virtual tour of our influencer management studio and see where dreams become reality!</p>
                <a href="https://bioscopestudio.com/" target="_blank">Visit us</a>
            </div>
        </div>
        <div class="card">
            <div class="circle">
                <h2><u>Join Us</u></h2>
            </div>
            <div class="content">
                <p>Unlock the potential of your social media presence with personalized influencer management strategies
                    tailored just for you.</p>
                <a href="b_login.php">Connect</a>
            </div>
        </div>
    </div>

    <section class="featured-influencer">
        <div class="container">
            <h2><u>Featured Models</u></h2>
            <div class="row">
                <?php foreach ($top_influencers as $influencer): ?>
                    <div class="col-md-4">
                        <div class="influencer-item">
                            <img loading="lazy" src="<?php echo htmlspecialchars($influencer['profile_picture']); ?>"
                                class="img-fluid" alt="<?php echo htmlspecialchars($influencer['username']); ?>">
                            <div class="overlay">
                                <h1><?php echo htmlspecialchars($influencer['username']); ?></h1>
                                <h1><?php echo htmlspecialchars($influencer['state']); ?></h1>
                                <h1><?php echo $influencer['insta_followers']; ?> Followers</h1>
                                <div class="details">
                                    <form action="profile.php" method="get">
                                        <input type="hidden" name="user_id"
                                            value="<?php echo htmlspecialchars($influencer['id']); ?>">
                                        <?php if (isset($_SESSION['username'])): ?>
                                            <button type="submit" class="btn" style="background: #064439; color: white;">View
                                                Profile</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-dark btn-disabled" disabled>Login to
                                                access profile...</button>
                                        <?php endif; ?>
                                    </form>
                                </div> <!-- Close .details -->
                            </div> <!-- Close .overlay -->
                        </div> <!-- Close .influencer-item -->
                    </div> <!-- Close .col-md-4 -->
                <?php endforeach; ?>
            </div> <!-- Close .row -->
        </div> <!-- Close .container -->
    </section> <!-- Close .featured-influencer -->

    <!-- New Sections -->

    <section class="testimonials">
        <div class="container">
            <h2><u>What Our Clients Say</u></h2>
            <div class="testimonial-wrapper">
                <div class="testimonial-inner">
                    <div class="testimonial-item">
                        <blockquote>
                            <p><strong>"Bioscope Media has helped me connect with amazing brands and grow my audience
                                    significantly!"</strong></p>
                            <h5>- Alex Johnson</h5>
                        </blockquote>
                    </div>
                    <div class="testimonial-item">
                        <blockquote>
                            <p><strong>"The management team is incredible. They really care about my success and
                                    provide
                                    excellent support."</strong></p>
                            <h5>- Maria Rodriguez</h5>
                        </blockquote>
                    </div>
                    <div class="testimonial-item">
                        <blockquote>
                            <p><strong>"I love the creative content ideas they provide. My social media has never
                                    looked
                                    better!"</strong></p>
                            <h5>- Emily Davis</h5>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <footer>
        <section class="top">
            <img loading="lazy" src="media/bioscope-icon.png" />
            <ul>
                <li>
                    <h3>Company</h3>
                    <a>About Us</a>
                    <a>Blog</a>
                    <a href="register.php">Join Us</a>
                    <a href="model.php">Careers</a>
                    <a href="brand_auth.php">Join as brand</a>
                </li>
            </ul>
        </section>
        <section class="bottom">© 2024 Bioscope Media</section>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js'></script>
    <script src="index.js"></script>
</body>

</html>