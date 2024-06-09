


<?php
// Database connection 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sk_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $id = $_POST['id'];
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $last_name = $_POST['last_name'];
  $gender = $_POST['gender'];
  $contact_no = $_POST['contact_no'];
  $committee = $_POST['committee'];
  $barangay_position = $_POST['barangay_position'];

  // File upload handling
  if ($_FILES['profile_picture']['name']) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Improved Checks:
    if (!getimagesize($_FILES["profile_picture"]["tmp_name"])) {
      echo "Error: Invalid image file.";
      $uploadOk = 0;
    }

    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif']; 
    if (!in_array($imageFileType, $allowedFormats)) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }

    if ($_FILES["profile_picture"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }

    // Attempt upload if no errors
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $profile_picture = basename($_FILES["profile_picture"]["name"]);

        // Update with image
        $sql = "UPDATE barangay_official SET 
                    first_name = ?, middle_name = ?, last_name = ?,  
                    gender = ?, contact_no = ?, committee = ?, 
                    barangay_position = ?, profile_picture = ?  
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi', $first_name, $middle_name, $last_name, $gender, $contact_no, $committee, $barangay_position, $profile_picture, $id);

        if ($stmt->execute()) {
          header("Location: barangay-official-list.php");
          exit(); 
        } else {
          echo "Error updating record: " . $conn->error;
        }

        $stmt->close(); 
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  } else { // Prepare update query without profile picture
    $sql = "UPDATE barangay_official SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', gender='$gender', contact_no='$contact_no', committee='$committee', barangay_position='$barangay_position' WHERE id=$id";

    // Execute update query
    if ($conn->query($sql) === TRUE) {
        // echo "Record updated successfully";
        header("Location: barangay-official-list.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
  }
}

$conn->close();
?>
