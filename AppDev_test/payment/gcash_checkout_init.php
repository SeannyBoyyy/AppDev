<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include('../config/connectDb.php');

// Check if the form was submitted
if (isset($_FILES["gcashReceipt"]) && isset($_POST["loggedInUserID"])) {
    // Get and sanitize the form inputs
    $loggedInUserID = intval($_POST["loggedInUserID"]);
    $subscriberID = htmlspecialchars(trim($_POST["subscriberID"]));
    $subscriberName = htmlspecialchars(trim($_POST["subscriberName"]));
    $subscriberEmail = filter_var($_POST["subscriberEmail"], FILTER_VALIDATE_EMAIL);
    
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

    // Insert data into database using prepared statements
    $query = "INSERT INTO user_subscriptions (user_id, status, image, subscriber_id, subscriber_name, subscriber_email) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'isssss', $loggedInUserID, $status, $newImageName, $subscriberID, $subscriberName, $subscriberEmail);

        if (mysqli_stmt_execute($stmt)) {
            // Retrieve the subscription ID of the newly inserted row
            $subscriptionID = mysqli_insert_id($conn);

            // Update subscription ID in user_accounts table
            $updateQuery = "UPDATE user_accounts SET subscription_id = ? WHERE id = ?";
            if ($updateStmt = mysqli_prepare($conn, $updateQuery)) {
                mysqli_stmt_bind_param($updateStmt, 'ii', $subscriptionID, $loggedInUserID);

                if (mysqli_stmt_execute($updateStmt)) {
                    // Redirect to approval page
                    session_start();
                    $_SESSION['acc_info'] = $loggedInUserID; // suspend acc
                    echo "...";
                    echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'GCash receipt uploaded successfully!',
                                icon: 'success'
                            }).then(function() {
                                window.location = 'approval.php';
                            });
                          </script>";
                    exit();
                } else {
                    // Error updating subscription ID
                    echo "...";
                    echo "<script>
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error: Unable to update subscription ID!',
                                icon: 'error'
                            }).then(function() {
                                window.location = 'index.php';
                            });
                          </script>";
                          
                    exit();
                }
            } else {
                echo "...";
                echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error: Unable to prepare update statement!',
                            icon: 'error'
                        }).then(function() {
                            window.location = 'index.php';
                        });
                      </script>";
                exit();
            }
        } else {
            // Error inserting user subscription data
            echo "...";
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error: Unable to upload GCash receipt!',
                        icon: 'error'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                  </script>";
            exit();
        }
    } else {
        echo "...";
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: Unable to prepare insert statement!',
                    icon: 'error'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
        exit();
    }
} else {
    // If form was not submitted
    echo "...";
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error: Form was not submitted!',
                icon: 'error'
            }).then(function() {
                window.location = 'index.php';
            });
          </script>";
    exit();
}
?>
