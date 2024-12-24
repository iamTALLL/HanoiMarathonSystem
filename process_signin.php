<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "hana";
$password = "Ahihi1002:>";
$dbname = "hanoi_marathon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query to fetch user data from the 'user' table
    $sql = "SELECT userID, password FROM user WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if the username exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Store user information in session
                $_SESSION['userID'] = $id;
                $_SESSION['username'] = $username;

                // Fetch participantID
                $participantSql = "SELECT participantID FROM participants WHERE userID = ?";
                if ($pstmt = $conn->prepare($participantSql)) {
                    $pstmt->bind_param("i", $id); // Sử dụng $id
                    $pstmt->execute();
                    $pstmt->bind_result($participantID);
                    if ($pstmt->fetch()) {
                        $_SESSION['participantID'] = $participantID;
                    }
                    $pstmt->close();
                }

                // Redirect to participant page
                header("Location: participant.php");
                exit();
            } else {
                echo "Invalid password. <a href='signin.html'>Try again</a>";
            }
        } else {
            echo "No account found with that username. <a href='signin.html'>Try again</a>";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
