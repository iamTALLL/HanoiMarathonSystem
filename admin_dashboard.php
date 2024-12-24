<?php
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: adminsignin.html"); // Redirect to login if not logged in
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

// Fetch pending participants (status = 'pending')
$pendingSql = "SELECT mp.entryNumber, p.name, p.email, r.raceName, r.date, r.time, mp.status, mp.participantID, mp.raceID 
               FROM marathonparticipants mp
               JOIN participants p ON mp.participantID = p.participantID
               JOIN race r ON mp.raceID = r.raceID
               WHERE mp.status = 'pending'"; // Only fetch pending races

$pendingResult = $conn->query($pendingSql);

// Fetch approved participants (status = 'approved')
$approvedSql = "SELECT mp.entryNumber, p.name, p.email, r.raceName, r.date, r.time, mp.status, mp.participantID, mp.raceID 
                FROM marathonparticipants mp
                JOIN participants p ON mp.participantID = p.participantID
                JOIN race r ON mp.raceID = r.raceID
                WHERE mp.status = 'approved'"; // Only fetch approved races

$approvedResult = $conn->query($approvedSql);

// Handle approval or rejection for each race individually
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        // Approve the participant for a specific race
        $participantID = $_POST['approve'];
        $raceID = $_POST['raceID'];

        // Approve the participant's registration for this race
        $updateSql = "UPDATE marathonparticipants SET status = 'approved' WHERE participantID = ? AND raceID = ?";
        if ($stmt = $conn->prepare($updateSql)) {
            $stmt->bind_param("ii", $participantID, $raceID);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST['reject'])) {
        // Reject the participant for a specific race
        $participantID = $_POST['reject'];
        $raceID = $_POST['raceID'];

        // Reject the participant's registration for this race
        $updateSql = "UPDATE marathonparticipants SET status = 'rejected' WHERE participantID = ? AND raceID = ?";
        if ($stmt = $conn->prepare($updateSql)) {
            $stmt->bind_param("ii", $participantID, $raceID);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Reload the page after action is completed
    header("Location: admin_dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <a href="logout.php">Log Out</a>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="info.php">Informations</a>
        </div>
    </div>

    <div class="container">
        <h1>Pending Approvals</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Race</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pendingResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['raceName']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                        <td>
                            <!-- Approve and Reject buttons for each race -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="raceID" value="<?php echo $row['raceID']; ?>">
                                <button type="submit" name="approve" value="<?php echo $row['participantID']; ?>">Approve</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="raceID" value="<?php echo $row['raceID']; ?>">
                                <button type="submit" name="reject" value="<?php echo $row['participantID']; ?>">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h1>Approved Participants</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Race</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $approvedResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['raceName']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
