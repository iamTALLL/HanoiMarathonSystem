<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: participant.php"); // Redirect to dashboard if not logged in
    exit();
}

$servername = "localhost";
$username = "hana";
$password = "Ahihi1002:>";
$dbname = "hanoi_marathon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the participantID from the session
$participantID = $_SESSION['participantID'];  // Assuming userID is the same as participantID

// Fetch all raceID and status from marathonparticipants
$sql1 = "SELECT raceID, status 
         FROM marathonparticipants 
         WHERE participantID = ?";  // Use participantID to fetch raceID and status

$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $participantID);
$stmt1->execute();
$result1 = $stmt1->get_result();

// Initialize an array to store race details
$raceDetails = [];

// Fetch race details for each raceID
while ($row1 = $result1->fetch_assoc()) {
    $raceID = $row1['raceID'];

    // Fetch race details using the raceID
    $sql2 = "SELECT raceName, date, time 
             FROM race 
             WHERE raceID = ?";
    
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $raceID);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($row2 = $result2->fetch_assoc()) {
        $raceDetails[] = [
            'raceName' => $row2['raceName'],
            'date' => $row2['date'],
            'time' => $row2['time'],
            'status' => $row1['status']
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Races</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .approved {
            color: green;
        }
        .pending {
            color: yellow;
        }
        .rejected {
            color: red;
        }
    </style>
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
            <a href="yourrace.php">Your Race</a> <!-- Added Your Race link -->
        </div>
    </div>

    <div class="container">
        <h1>Your Registered Races</h1>

        <table>
            <thead>
                <tr>
                    <th>Race Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Check if there are any race details
                if (!empty($raceDetails)):
                    foreach ($raceDetails as $race):
                ?>
                        <tr>
                            <td><?php echo $race['raceName']; ?></td>
                            <td><?php echo $race['date']; ?></td>
                            <td><?php echo $race['time']; ?></td>
                            <td class="<?php echo strtolower($race['status']); ?>">
                                <?php echo ucfirst($race['status']); ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="4">No races registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
