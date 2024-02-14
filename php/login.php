<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "Hello, World!";
} else {
    // Establish MySQL database connection
    $servername = "localhost";
    $username = "nirravsawla";
    $password = "170705";
    $database = "bioscope"; // Your database name
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect form data
        $name = $_POST['i_name'];
        $ig = $_POST['ig'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];

        // Prepare and bind SQL statement
        $sql = "INSERT INTO influencers (i_name, ig, email, pwd) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $ig, $email, $password);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close database connection
    $conn->close();
}
?>
