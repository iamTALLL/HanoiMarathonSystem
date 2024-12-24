<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "hana";
$password = "Ahihi1002:>";
$dbname = "hanoi_marathon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You need to log in first.";
    exit();
}

$userID = $_SESSION['userID'];
$raceID = $_GET['raceID']; // Assuming raceID is passed via URL

// Get accommodation details (hotelName, address, ward, district)
$sql = "SELECT hotelName, address, ward, district FROM accommodation WHERE participantID = (SELECT participantID FROM participants WHERE userID = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hotelName, $address, $ward, $district);

// Check if accommodation details are found
if ($stmt->fetch()) {
    // Combine the address fields into one curaddress
    $curaddress = $hotelName . ", " . $address . ", " . $ward . ", " . $district;

    // Now insert the participant into marathonparticipants table with curaddress
    $sql = "INSERT INTO marathonparticipants (raceID, participantID, curaddress) VALUES (?, (SELECT participantID FROM participants WHERE userID = ?), ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $raceID, $userID, $curaddress);
        if ($stmt->execute()) {
            echo "You have successfully joined the race!";
            echo "<br><a href='participant.php'>Return</a>";
        } else {
            echo "Error joining the race: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement for marathonparticipants: " . $conn->error;
    }
} else {
    echo "No accommodation details found.";
}

$conn->close();
?>
