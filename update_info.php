<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['participantID'])) {
    header("Location: login.html");
    exit();
}

$participantID = $_SESSION['participantID'];

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "hana";
$password = "Ahihi1002:>";
$dbname = "hanoi_marathon";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Truy vấn thông tin người tham gia
$sql = "SELECT participantID, name, nationality, sex, age, racebib, passportID, curaddress, email, mobile FROM participants WHERE participantID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $participantID);
$stmt->execute();
$result = $stmt->get_result();
$participant = $result->fetch_assoc();

// Truy vấn kỷ lục quá khứ
$sqlRecord = "SELECT recordID, raceName, raceDate, marathonTime, standing FROM pastrecord WHERE participantID = ?";
$stmtRecord = $conn->prepare($sqlRecord);
$stmtRecord->bind_param("i", $participantID);
$stmtRecord->execute();
$recordResult = $stmtRecord->get_result();

$sqlAccommodation = "SELECT accommodationID, participantID, hotelName, district, ward, address FROM accommodation WHERE participantID = ?";
$stmtAccommodation = $conn->prepare($sqlAccommodation);
$stmtAccommodation->bind_param("i", $participantID);
$stmtAccommodation->execute();
$accomdationResult = $stmtAccommodation->get_result();

$accommodation = $accomdationResult->fetch_assoc();
if (!$accommodation) {
    $accommodation = array('hotelName' => '', 'district' => '', 'ward' => '', 'address' => '');
}

$stmt->close();
$stmtRecord->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
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

    <div class="container">
        <h1>Update Your Information</h1>
        
        <!-- Form cập nhật thông tin (Race Bib tách ra dưới) -->
        <form action="update_info.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($participant['name']); ?>" required><br>

            <label for="nationality">Nationality:</label>
            <input type="text" id="nationality" name="nationality" value="<?php echo htmlspecialchars($participant['nationality']); ?>" required><br>

            <label for="sex">Sex:</label>
            <select id="sex" name="sex">
                <option value="Male" <?php echo $participant['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $participant['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $participant['sex'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($participant['age']); ?>" required><br>

            <label for="passportID">Passport ID:</label>
            <input type="text" id="passportID" name="passportID" value="<?php echo htmlspecialchars($participant['passportID']); ?>" required><br>

            <label for="hotelName">Hotel Name</label>
        <input type="text" id="hotelName" name="hotelName" placeholder="Hotel name" 
            value="<?php echo htmlspecialchars($accommodation['hotelName']); ?>">

        <label for="district">District</label>
        <label for="district">District</label>
            <select id="district" name="district" required onchange="loadWards()">
                <option value="" disabled>Select your district</option>
                <option value="Hoan Kiem" <?php echo $accommodation['district'] == 'Hoan Kiem' ? 'selected' : ''; ?>>Hoàn Kiếm</option>
                <option value="Ba Dinh" <?php echo $accommodation['district'] == 'Ba Dinh' ? 'selected' : ''; ?>>Ba Đình</option>
                <option value="Dong Da" <?php echo $accommodation['district'] == 'Dong Da' ? 'selected' : ''; ?>>Đống Đa</option>
                <option value="Hai Ba Trung" <?php echo $accommodation['district'] == 'Hai Ba Trung' ? 'selected' : ''; ?>>Hai Bà Trưng</option>
                <option value="Cau Giay" <?php echo $accommodation['district'] == 'Cau Giay' ? 'selected' : ''; ?>>Cầu Giấy</option>
                <option value="Thanh Xuan" <?php echo $accommodation['district'] == 'Thanh Xuan' ? 'selected' : ''; ?>>Thanh Xuân</option>
                <option value="Ha Dong" <?php echo $accommodation['district'] == 'Ha Dong' ? 'selected' : ''; ?>>Hà Đông</option>
                <option value="Long Bien" <?php echo $accommodation['district'] == 'Long Bien' ? 'selected' : ''; ?>>Long Biên</option>
                <option value="Gia Lam" <?php echo $accommodation['district'] == 'Gia Lam' ? 'selected' : ''; ?>>Gia Lâm</option>
                <option value="Nam Tu Liem" <?php echo $accommodation['district'] == 'Nam Tu Liem' ? 'selected' : ''; ?>>Nam Từ Liêm</option>
                <option value="Bac Tu Liem" <?php echo $accommodation['district'] == 'Bac Tu Liem' ? 'selected' : ''; ?>>Bắc Từ Liêm</option>
                <option value="Tay Ho" <?php echo $accommodation['district'] == 'Tay Ho' ? 'selected' : ''; ?>>Tây Hồ</option>
                <option value="Son Tay" <?php echo $accommodation['district'] == 'Son Tay' ? 'selected' : ''; ?>>Sơn Tây</option>
                <option value="Me Linh" <?php echo $accommodation['district'] == 'Me Linh' ? 'selected' : ''; ?>>Mê Linh</option>
                <option value="Hoai Duc" <?php echo $accommodation['district'] == 'Hoai Duc' ? 'selected' : ''; ?>>Hoài Đức</option>
                <option value="Phu Xuyen" <?php echo $accommodation['district'] == 'Phu Xuyen' ? 'selected' : ''; ?>>Phú Xuyên</option>
                <option value="Thanh Oai" <?php echo $accommodation['district'] == 'Thanh Oai' ? 'selected' : ''; ?>>Thanh Oai</option>
                <option value="Thuong Tin" <?php echo $accommodation['district'] == 'Thuong Tin' ? 'selected' : ''; ?>>Thường Tín</option>
                <option value="Phuc Tho" <?php echo $accommodation['district'] == 'Phuc Tho' ? 'selected' : ''; ?>>Phúc Thọ</option>
                <option value="Ba Vi" <?php echo $accommodation['district'] == 'Ba Vi' ? 'selected' : ''; ?>>Ba Vì</option>
                <option value="Dan Phuong" <?php echo $accommodation['district'] == 'Dan Phuong' ? 'selected' : ''; ?>>Đan Phượng</option>
                <option value="Chuong My" <?php echo $accommodation['district'] == 'Chuong My' ? 'selected' : ''; ?>>Chương Mỹ</option>
                <option value="Thanh Tri" <?php echo $accommodation['district'] == 'Thanh Tri' ? 'selected' : ''; ?>>Thanh Trì</option>
                <option value="Dong Anh" <?php echo $accommodation['district'] == 'Dong Anh' ? 'selected' : ''; ?>>Đông Anh</option>
                <option value="Soc Son" <?php echo $accommodation['district'] == 'Soc Son' ? 'selected' : ''; ?>>Sóc Sơn</option>
            </select>

            <label for="ward">Ward</label>
            <select id="ward" name="ward">
                <option value="<?php echo $accommodation['ward'] ?>" disabled>Select your ward</option>
                <!-- Ward options will be populated here based on district selection -->
            </select>

            <script>
                function loadWards() {
                    var district = document.getElementById('district').value;
                    var wardSelect = document.getElementById('ward');
                    
                    // Define wards for each district
                    var wards = {
                        "Hoan Kiem": ["Hàng Bài", "Hàng Bạc", "Hàng Buồm", "Phúc Tân", "Chương Dương", "Cầu Gỗ", "Tràng Tiền", "Hàng Gai"],
                        "Ba Dinh": ["Ngọc Hà", "Liên Bảo", "Dịch Vọng", "Phúc Xá", "Trúc Bạch", "Vạn Bảo", "Nguyễn Trung Trực"],
                        "Dong Da": ["Cát Linh", "Hàng Bột", "Trung Liệt", "Láng Hạ", "Khâm Thiên", "Thịnh Quang"],
                        "Hai Ba Trung": ["Quỳnh Mai", "Thanh Lương", "Bạch Mai", "Trương Định", "Mai Động", "Vĩnh Tuy"],
                        "Cau Giay": ["Dịch Vọng Hậu", "Cầu Giấy", "Mai Dịch", "Trung Hòa", "Yên Hòa"],
                        "Thanh Xuan": ["Hiệp Thanh", "Thanh Xuân Nam", "Thanh Xuân Bắc", "Khương Trung", "Linh Đàm"],
                        "Ha Dong": ["Biên Giang", "Yên Nghĩa", "Quốc Oai", "Mỹ Đình", "Phú Lương"],
                        "Long Bien": ["Ngọc Thụy", "Gia Thuỵ", "Bồ Đề", "Cự Khối", "Long Biên"],
                        "Gia Lam": ["Gia Lâm", "Cổ Bi", "Bắc Sơn", "Dương Xá", "Đình Xuyên"],
                        "Nam Tu Liem": ["Mỹ Đình 1", "Mỹ Đình 2", "Cầu Diễn", "Phú Diễn"],
                        "Bac Tu Liem": ["Minh Khai", "Liên Mạc", "Cổ Nhuế", "Phú Diễn"],
                        "Tay Ho": ["Tây Hồ", "Quảng An", "Thụy Khuê", "Xuân La"],
                        "Son Tay": ["Sơn Tây", "Đường Lâm", "Thanh Mỹ"],
                        "Me Linh": ["Mê Linh", "Tiến Thắng", "Quang Minh"],
                        "Hoai Duc": ["An Khánh", "Di Trạch", "Vân Canh", "Kim Chung"],
                        "Phu Xuyen": ["Phú Minh", "Phú Xuyên", "Trường Thịnh"],
                        "Thanh Oai": ["Thanh Oai", "Bình Minh"],
                        "Thuong Tin": ["Thường Tín", "Vĩnh Quỳnh"],
                        "Phuc Tho": ["Phúc Thọ", "Vân Hòa"],
                        "Ba Vi": ["Ba Vì", "Vân Hòa"],
                        "Dan Phuong": ["Đan Phượng", "Hát Môn"],
                        "Chuong My": ["Chương Mỹ", "Lê Lợi"],
                        "Thanh Tri": ["Thanh Trì", "Tả Thanh Oai"],
                        "Dong Anh": ["Đông Anh", "Xuân Canh", "Mai Lâm"],
                        "Soc Son": ["Phú Cường", "Sóc Sơn", "Nam Sơn"]
                    };
                    
                    while (wardSelect.options.length > 1) {
                        wardSelect.remove(1);
                    }

                    if (wards[district]) {
                        // Add the default "Select your ward" option
                        var defaultOption = document.createElement('option');
                        defaultOption.text = 'Select your ward';
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        wardSelect.appendChild(defaultOption);

                        // Add new ward options to the select dropdown
                        wards[district].forEach(function(ward) {
                            var option = document.createElement('option');
                            option.value = ward;
                            option.text = ward;

                            // Set the previously selected ward as selected
                            if (ward === "<?php echo $accommodation['ward']; ?>") {
                                option.selected = true;
                            }

                            wardSelect.appendChild(option);
                        });
                    }
                }
            </script>


        <label for="address">Full Address</label>
        <textarea id="address" name="address" rows="3" placeholder="Enter full address" required>
        <?php echo htmlspecialchars($accommodation['address']); ?>
        </textarea>


            <label for="curaddress">Current Address:</label>
            <input type="text" id="curaddress" name="curaddress" value="<?php echo htmlspecialchars($participant['curaddress']); ?>" readonly>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($participant['email']); ?>" required><br>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($participant['mobile']); ?>" required><br>

            <h2>Race Bib Information</h2>
            <label for="raceName">Race Name:</label>
            <input type="text" id="raceName" name="raceName"><br>

            <label for="raceDate">Race Date:</label>
            <input type="date" id="raceDate" name="raceDate"><br>

            <label for="marathonTime">Marathon Time:</label>
            <input type="text" id="marathonTime" name="marathonTime"><br>

            <label for="standing">Standing:</label>
            <input type="text" id="standing" name="standing"><br>
            
            <label for="racebib">Race Bib:</label>
            <input type="text" id="racebib" name="racebib" value="<?php echo htmlspecialchars($participant['racebib']); ?>" readonly><br>

            <input type="submit" name="update" value="Update Information">
        </form>

        <!-- Kỷ lục quá khứ -->
        <h2>Past Race Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Race Name</th>
                    <th>Race Date</th>
                    <th>Marathon Time</th>
                    <th>Standing</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $recordResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['raceName']); ?></td>
                        <td><?php echo htmlspecialchars($row['raceDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['marathonTime']); ?></td>
                        <td><?php echo htmlspecialchars($row['standing']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php
    // Cập nhật thông tin khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
        $name = $_POST['name'];
        $nationality = $_POST['nationality'];
        $sex = $_POST['sex'];
        $age = $_POST['age'];
        $passportID = $_POST['passportID'];
        $hotelName = $_POST['hotelName'];
        $district = $_POST['district'];
        $ward = $_POST['ward'];
        $curaddress = $address . ', ' . $ward . ', ' . $district;
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $racebib = '';
        $raceName = $_POST['raceName'];
        $raceDate = $_POST['raceDate'];
        $marathonTime = $_POST['marathonTime'];
        $standing = $_POST['standing'];
        
        
        
        $sqlInsertRecord = "INSERT INTO pastrecord (participantID, raceName, raceDate, marathonTime, standing) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmtInsertRecord = $conn->prepare($sqlInsertRecord);
        $stmtInsertRecord->bind_param("issss", $participantID, $raceName, $raceDate, $marathonTime, $standing);
        $stmtInsertRecord->execute();
        
        $sql = "SELECT raceName, standing FROM pastrecord WHERE participantID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $participantID);
        $stmt->execute();
        $result = $stmt->get_result();
        
    
        if ($stmtInsertRecord->affected_rows > 0) {
            // Tự động cập nhật racebib
            $sqlGetRacebib = "SELECT raceName, standing FROM pastrecord WHERE participantID = ?";
            $stmtGetRacebib = $conn->prepare($sqlGetRacebib);
            $stmtGetRacebib->bind_param("i", $participantID);
            $stmtGetRacebib->execute();
            $result = $stmtGetRacebib->get_result();
    
            $racebib = '';
            while ($row = $result->fetch_assoc()) {
                    $racebib .= $row['raceName'] . '-' . $row['standing'] . ' ';
                
            }
            $racebib = trim($racebib); // Loại bỏ khoảng trắng dư
    
            // Lưu racebib vào bảng participants
            $updateRacebibSql = "UPDATE participants SET racebib = ? WHERE participantID = ?";
            $stmtUpdateRacebib = $conn->prepare($updateRacebibSql);
            $stmtUpdateRacebib->bind_param("si", $racebib, $participantID);
            $stmtUpdateRacebib->execute();
    
            echo "<p>Race record added and Race Bib updated successfully!</p>";
            $stmtUpdateRacebib->close();
            $stmtGetRacebib->close();
        } else {
            echo "<p>Failed to add race record.</p>";
        }

        $sqlCheckAccommodation = "SELECT accommodationID FROM accommodation WHERE participantID = ?";
        $stmtCheck = $conn->prepare($sqlCheckAccommodation);
        $stmtCheck->bind_param("i", $participantID);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            // Đã có bản ghi accommodation -> Cập nhật
            $sqlUpdateAccommodation = "UPDATE accommodation SET hotelName = ?, district = ?, ward = ?, address = ? WHERE participantID = ?";
            $stmtUpdateAccommodation = $conn->prepare($sqlUpdateAccommodation);
            $stmtUpdateAccommodation->bind_param("ssssi", $hotelName, $district, $ward, $address, $participantID);
        } else {
            // Chưa có bản ghi accommodation -> Thêm mới
            $sqlInsertAccommodation = "INSERT INTO accommodation (participantID, hotelName, district, ward, address) VALUES (?, ?, ?, ?, ?)";
            $stmtUpdateAccommodation = $conn->prepare($sqlInsertAccommodation);
            $stmtUpdateAccommodation->bind_param("issss", $participantID, $hotelName, $district, $ward, $address);
        }
        $stmtUpdateAccommodation->execute();

        if ($stmtUpdateAccommodation->affected_rows > 0) {
            echo "<p>Accommodation information updated successfully!</p>";
        } else {
            echo "<p>No changes were made to accommodation.</p>";
        }

        // Cập nhật bảng participants
        $updateSql = "UPDATE participants SET name = ?, nationality = ?, sex = ?, age = ?, passportID = ?, curaddress = ?, email = ?, mobile = ? WHERE participantID = ?";
        $stmtUpdate = $conn->prepare($updateSql);
        $stmtUpdate->bind_param("sssiisssi", $name, $nationality, $sex, $age, $passportID, $curaddress, $email, $mobile, $participantID);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows > 0) {
            echo "<p>Participant information updated successfully!</p>";
        } else {
            echo "<p>No changes were made to participant information.</p>";
        }

        // Cập nhật thông tin
        $updateSql = "UPDATE participants SET name = ?, nationality = ?, sex = ?, age = ?, passportID = ?, curaddress = ?, email = ?, mobile = ? WHERE participantID = ?";
        $stmtUpdate = $conn->prepare($updateSql);
        $stmtUpdate->bind_param("sssiissssi", $name, $nationality, $sex, $age, $passportID, $curaddress, $email, $mobile, $participantID);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows > 0) {
            echo "<p>Information updated successfully!</p>";
        } else {
            echo "<p>No changes were made.</p>";
        }

        $stmtUpdate->close();
    }
    

    $conn->close();
    ?>
</body>
</html>
