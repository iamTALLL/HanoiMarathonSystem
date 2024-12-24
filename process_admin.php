<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "hana";
$password = "Ahihi1002:>";
$dbname = "hanoi_marathon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý đăng nhập admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_username = trim($_POST['admin_username']);
    $admin_password = trim($_POST['admin_password']);

    // Truy vấn để lấy thông tin admin từ bảng administrator
    $sql = "SELECT adminID, password FROM administrator WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $stmt->store_result();

        // Kiểm tra nếu username tồn tại
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($adminID, $hashedPassword); // Lấy mật khẩu đã mã hóa
            $stmt->fetch();

            // Kiểm tra mật khẩu
            if (password_verify($admin_password, $hashedPassword)) { // Dùng password_verify để so sánh
                // Lưu thông tin admin vào session
                $_SESSION['adminID'] = $adminID;
                $_SESSION['admin_username'] = $admin_username;

                // Chuyển hướng đến trang quản trị (ví dụ: trang dashboard)
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Invalid passwordpassword! <a href='adminsignin.php'>Try again</a>";
            }
        } else {
            echo "No account found with that username. <a href='adminsignin.html'>Try againagain</a>";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
