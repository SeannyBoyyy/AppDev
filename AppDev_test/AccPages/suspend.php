<?php 
include('../navbars/login-homepage.php'); 
include('../config/connectDb.php');

session_start();
if(isset($_SESSION['acc_info'])){
    $business_owner = $_SESSION['acc_info']; 
} else {
    echo 'no owner ';
    echo "<script>
            window.location = '../AccPages/login-page.php';
        </script>";
    exit();
}

// Function to save admin message
function saveAdminMessage($conn, $email, $subject, $message) {
    $query = "INSERT INTO admin_messages (email, subject, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $subject, $message);
    return $stmt->execute();
}

// Function to update user status
function updateUserStatus($conn, $email, $status) {
    $query = "UPDATE user_accounts SET status = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status, $email);
    return $stmt->execute();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if(empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Please fill in all the fields.',
            icon: 'error'
        });
        </script>";
    } else {
        // Form data
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        
        // Save admin message
        $admin_message_saved = saveAdminMessage($conn, $email, $subject, $message);

        // Update user status
        $user_status_updated = updateUserStatus($conn, $email, 'SUSPEND');

        if($admin_message_saved && $user_status_updated) {
            echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Message sent successfully!',
                icon: 'success'
            }).then(function() {
                window.location = '#';
            });
            </script>";

        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error saving message or updating user status.',
                icon: 'error'
            });
            </script>";
        }
    }
}
?>  

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Account Suspended</h3>
                        <p class="card-text text-center">Your account has been suspended. Please wait for the admin to
                            resolve the issue.</p>
                        <hr>
                        <h5 class="card-title text-center mb-3">Contact Admin</h5>
                        <form action="suspend.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
