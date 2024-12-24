<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="./image/logo.png" alt="Logo"> 
        <div class="race-info">
            <span class="name">HANOI MARATHON SYSTEM</span>
        </div>
        <div class="nav-buttons">
            <a href="participant.php">Dashboard</a>
            <a href="logout.php">Log Out</a>
            <a href="update_info.php">Update Information</a>
            <a href="info.php">Information</a>
            <a href="yourrace.php">Your Race</a>
        </div>
    </div>

    <h1>Available Races</h1>
    <div class="race-list">
        <?php
        // Fetch race data from the database
        $servername = "localhost";
        $username = "hana";
        $password = "Ahihi1002:>";
        $dbname = "hanoi_marathon";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the participant's ID from the session (make sure the user is logged in)
        session_start();
        $participantID = $_SESSION['participantID'];  // Assuming participantID is stored in session

        // Get available races
        $sql = "SELECT raceID, raceName, date, time FROM race";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Check if participant has already joined this race
                $checkSql = "SELECT * FROM marathonparticipants WHERE participantID = ? AND raceID = ?";
                if ($stmt = $conn->prepare($checkSql)) {
                    $stmt->bind_param("ii", $participantID, $row['raceID']);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    // If participant already joined, show 'Joined' button
                    if ($stmt->num_rows > 0) {
                        $buttonText = "Joined";
                        $disabled = "disabled";  // Disable the button
                        $btnClass = "joined-btn";
                    } else {
                        $buttonText = "Join";
                        $disabled = "";  // Enable the button
                        $btnClass = "join-btn";
                    }

                    // Display race details with button
                    echo "<div class='race-item'>";
                    echo "<h3>" . $row['raceName'] . "</h3>";
                    echo "<p>Date: " . $row['date'] . "</p>";
                    echo "<p>Time: " . $row['time'] . "</p>";
                    echo "<a href='join_race.php?raceID=" . $row['raceID'] . "' class='$btnClass' $disabled>$buttonText</a>";
                    echo "</div>";
                }
            }
        } else {
            echo "No races available.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
