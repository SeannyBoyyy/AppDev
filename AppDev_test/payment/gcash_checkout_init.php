<?php
include('../config/connectDb.php');

// Check if the form was submitted
if (isset($_FILES["gcashReceipt"]) && isset($_POST["loggedInUserID"])) {
    // Get the logged-in user ID
    $loggedInUserID = $_POST["loggedInUserID"];

    // Handle uploaded image
    $fileName = $_FILES["gcashReceipt"]["name"];
    $fileSize = $_FILES["gcashReceipt"]["size"];
    $tmpName = $_FILES["gcashReceipt"]["tmp_name"];

    // Check if image exists
    if ($_FILES["gcashReceipt"]["error"] === 4) {
        echo "<script>
                alert('Error: Image does not exist!');
                window.location = 'index.php';
              </script>";
        exit();
    }

    // Check image file extension
    $validImageExtensions = ['jpg', 'jpeg', 'png'];
    $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    if (!in_array(strtolower($imageExtension), $validImageExtensions)) {
        echo "<script>
                alert('Error: Invalid image extension!');
                window.location = 'index.php';
              </script>";
        exit();
    }

    // Check image file size
    if ($fileSize > 10000000) {
        echo "<script>
                alert('Error: Image size is too large!');
                window.location = 'index.php';
              </script>";
        exit();
    }

    // Generate new image name
    $newImageName = uniqid() . '.' . $imageExtension;

    // Define upload path
    $uploadPath = '../ProfileModule/img/' . $newImageName;

    // Move uploaded file to upload path
    move_uploaded_file($tmpName, $uploadPath);

    // Set status to "pending"
    $status = "PENDING";

    // Insert data into database
    $query = "INSERT INTO user_subscriptions (user_id, status, image) VALUES ($loggedInUserID, '$status', '$newImageName')";

    if (mysqli_query($conn, $query)) {
        // Retrieve the subscription ID of the newly inserted row
        $subscriptionID = mysqli_insert_id($conn);

        // Update subscription ID in user_accounts table
        $updateQuery = "UPDATE user_accounts SET subscription_id = $subscriptionID WHERE id = $loggedInUserID";

        if (mysqli_query($conn, $updateQuery)) {
            // Redirect to approval page
            session_start();
            $_SESSION['acc_info'] = $loggedInUserID; // suspend acc
            echo "<script>
                    alert('GCash receipt uploaded successfully!');
                    window.location = 'approval.php';
                  </script>";
            exit();
        } else {
            // Error updating subscription ID
            echo "<script>
                    alert('Error: Unable to update subscription ID!');
                    window.location = 'index.php';
                  </script>";
            exit();
        }
    } else {
        // Error inserting user subscription data
        echo "<script>
                alert('Error: Unable to upload GCash receipt!');
                window.location = 'index.php';
              </script>";
        exit();
    }
} else {
    // If form was not submitted
    echo "<script>
            alert('Error: Form was not submitted!');
            window.location = 'index.php';
          </script>";
    exit();
}
?>
