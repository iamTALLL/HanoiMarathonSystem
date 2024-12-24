<?php
// Mã hóa mật khẩu bằng hàm password_hash()
$password = 'Ahihi1002:>';  // Thay bằng mật khẩu bạn muốn mã hóa
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Sử dụng phương thức mã hóa mặc định của PHP

echo "Mật khẩu đã mã hóa: " . $hashedPassword;
?>
