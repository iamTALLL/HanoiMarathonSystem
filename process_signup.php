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

// Handle form submission for signup1
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $nationality = trim($_POST['nationality']);
    $sex = trim($_POST['sex']);
    $age = trim($_POST['age']);
    $passportID = isset($_POST['passportID']) ? trim($_POST['passportID']) : NULL;
    $hotelName = isset($_POST['hotelName']) && !empty($_POST['hotelName']) ? trim($_POST['hotelName']) : NULL;
    $district = trim($_POST['district']);
    $ward = trim($_POST['ward']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    
    // Check if all required fields are filled
    if ($name && $nationality && $sex && $age && $district && $ward && $address && $email && $mobile) {
        
        // Combine the fields into curaddress
        $curaddress = '';
        
        // Add hotelName if available
        if ($hotelName) {
            $curaddress .= $hotelName . ', ';
        }

        // Add address, ward, and district to curaddress
        $curaddress .= $address . ', ' . $ward . ', ' . $district;

        // Remove trailing comma and space if present
        $curaddress = rtrim($curaddress, ', ');

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Insert into the user table (store email and mobile only for now)
            $sql = "INSERT INTO user (email, mobile) VALUES (?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $email, $mobile);
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting user: " . $stmt->error);
                }
                $userID = $stmt->insert_id; // Get the last inserted user ID
                $stmt->close();

                // Store userID in session
                $_SESSION['userID'] = $userID;
            } else {
                throw new Exception("Error preparing the statement for user: " . $conn->error);
            }

            // Insert into the participants table
            $sql = "INSERT INTO participants (name, nationality, sex, age, passportID, curaddress, email, mobile, userID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssssiisss", $name, $nationality, $sex, $age, $passportID, $curaddress, $email, $mobile, $userID);
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting participant: " . $stmt->error);
                }
                $participantID = $stmt->insert_id; // Get the last inserted participant ID
                $stmt->close();
            } else {
                throw new Exception("Error preparing the statement for participants: " . $conn->error);
            }

            // Insert into the accommodation table
            $sql = "INSERT INTO accommodation (participantID, hotelName, district, ward, address) 
                    VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("issss", $participantID, $hotelName, $district, $ward, $address);
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting accommodation: " . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception("Error preparing the statement for accommodation: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();
            echo "New participant, accommodation, and user added successfully!<br>";
            echo "<a href='signup2.html'>Continue</a>";
            header("Location: signup2.html");
            exit();
        } catch (Exception $e) {
            // Rollback if there is an error
            $conn->rollback();
            echo "Transaction failed: " . $e->getMessage();
        }
    } else {
        echo "Please fill in all required fields!<br>";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
