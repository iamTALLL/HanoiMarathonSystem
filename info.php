<?php
// Start the session
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['participantID'])) {
    header("Location: login.html");
    exit();
}

$participantID = $_SESSION['participantID'];

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

// Fetch participant information based on session ID
$sql = "SELECT participantID, name, age, sex, email, mobile, racebib, passportID, curaddress FROM participants WHERE participantID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $participantID);
$stmt->execute();
$result = $stmt->get_result();
$participant = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Information</title>
    <link href="./css/style.css" rel="stylesheet">
    
    <style>
        .container {
            max-width: 600px;
            margin: 100px auto; /* Thêm khoảng cách xa hơn giữa container và navbar */
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #004085; /* Màu xanh đậm cũ */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .info {
            padding: 20px;
        }
        .info p {
            font-size: 18px;
            margin: 10px 0;
            line-height: 1.5;
        }
        .info p span {
            font-weight: bold;
            color: #333; /* Giữ nguyên màu chữ cho phần nhãn */
        }
        .info p:last-child {
            margin-bottom: 0;
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
            <a href="yourrace.php">Your Race</a>
        </div>
    </div>

    <!-- Container hiển thị thông tin -->
    <div class="container">
        <div class="header">
            Participant Information
        </div>
        <div class="info">
            <p><span>Participant ID:</span> <?php echo htmlspecialchars($participant['participantID']); ?></p>
            <p><span>Full Name:</span> <?php echo htmlspecialchars($participant['name']); ?></p>
            <p><span>Age:</span> <?php echo htmlspecialchars($participant['age']); ?></p>
            <p><span>Sex:</span> <?php echo htmlspecialchars($participant['sex']); ?></p>
            <p><span>Email:</span> <?php echo htmlspecialchars($participant['email']); ?></p>
            <p><span>Mobile:</span> <?php echo htmlspecialchars($participant['mobile']); ?></p>
            <p><span>Race Bib:</span> <?php echo htmlspecialchars($participant['racebib'] ?? ''); ?></p>
            <p><span>Passport ID:</span> <?php echo htmlspecialchars($participant['passportID']); ?></p>
            <p><span>Current Address:</span> <?php echo htmlspecialchars($participant['curaddress']); ?></p>
            <p><span>Email:</span> <?php echo htmlspecialchars($participant['email']); ?></p>
            <p><span>Mobile:</span> <?php echo htmlspecialchars($participant['mobile']); ?></p>
        </div>
    </div>
</body>
</html>
