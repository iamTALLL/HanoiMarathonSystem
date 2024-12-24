<?php 
session_start(); // Start the session

// Connect to the database
$servername = "localhost";
$username = "hana";
$password = "Ahihi1002:>";
$dbname = "hanoi_marathon";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for signup2 (update username and password)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming userID is stored in the session after signup1
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $passwordAgain = trim($_POST['password_again']);

        // Check if passwords match
        if ($password === $passwordAgain) {
            // Hash the password before saving it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if username is provided and not NULL
            if (!empty($username)) {
                // Update username and password in the user table
                $sql = "UPDATE user SET username = ?, password = ? WHERE userID = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssi", $username, $hashedPassword, $userID);
                    if ($stmt->execute()) {
                        echo "Username and password updated successfully!";
                        echo "<br><a href='signin.html'>Sign in now!</a>";
                    } else {
                        echo "Error updating user: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing the statement for user update: " . $conn->error;
                }
            } else {
                echo "Please enter a username.";
            }
        } else {
            echo "Passwords do not match. Please try again.";
        }
    } else {
        echo "User not found. Please complete the first signup step.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
