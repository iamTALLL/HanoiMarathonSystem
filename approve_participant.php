<?php
session_start();

// Check if the user is admin and the required parameters are set
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && isset($_GET['entryNumber']) && isset($_GET['status'])) {
    $entryNumber = $_GET['entryNumber'];
    $status = $_GET['status']; // 'approved' or 'rejected'

    // Connect to database
    $servername = "localhost";
    $username = "hana";
    $password = "Ahihi1002:>";
    $dbname = "hanoi_marathon";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the participant's status and give entryNumber if approved
    if ($status == 'approved') {
        // Generate a random entryNumber (or you can use another method)
        $entryNumber = rand(1000, 9999);  // Example random entry number
        $sql = "UPDATE marathonparticipants SET status = ?, entryNumber = ? WHERE entryNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $status, $entryNumber, $entryNumber);
    } else {
        $sql = "UPDATE marathonparticipants SET status = ? WHERE entryNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $entryNumber);
    }

    if ($stmt->execute()) {
        echo "Participant status updated successfully.";
    } else {
        echo "Error updating participant status.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Access denied.";
}
?>
