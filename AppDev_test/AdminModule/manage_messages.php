<?php
include('../config/connectDb.php');

// Query to count messages
$sql = "SELECT COUNT(*) AS message_count FROM admin_messages";

// Execute query
$result = $conn->query($sql);

// Check if query executed successfully
if ($result) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();
    
    // Get the message count
    $msgCount = $row['message_count'];
} else {
    // Handle query error
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Fetch messages
$msgRows = array(); // Initialize an empty array to store messages
if ($msgCount > 0) {
    $sql = "SELECT * FROM admin_messages"; // Query to fetch messages
    $msgResult = $conn->query($sql);
    
    // Check if query executed successfully
    if ($msgResult) {
        // Fetch all messages and store them in $msgRows array
        while ($msgRow = $msgResult->fetch_assoc()) {
            $msgRows[] = $msgRow;
        }
    } else {
        // Handle query error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<?php if (!empty($msgRows)): ?>
    <div class="row d-flex align-items-start justify-content-start ">
        <h1>Messages</h1>
        <?php foreach ($msgRows as $index => $msgRow): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                <div class="card text-center">
                    <div class="card-header">
                        <?php echo $msgRow['email']; ?>
                    </div>
                    <div class="card-body">
                        <h4 class="card-text"><?php echo $msgRow['subject']; ?>.</h4>
                        <p class="card-text"><?php echo $msgRow['message']; ?>.</p>
                        <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $index; ?>">
                            <small>Message Back!</small>
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#90EE90" class="bi bi-chat-fill" viewBox="0 0 16 16">
                                <path d="M8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6-.097 1.016-.417 2.13-.771 2.966-.079.186.074.394.273.362 2.256-.37 3.597-.938 4.18-1.234A9 9 0 0 0 8 15"/>
                            </svg>
                        </button>
                    </div>
                    <div class="card-footer text-body-secondary">
                        <?php echo $msgRow['created_at']; ?>
                    </div>
                </div>
            </div>

            <!-- modal -->
            <div class="modal " id="exampleModal<?php echo $index ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">New message</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="send.php" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="staticEmail" class="col-form-label">Recipient:</label>
                                <input type="text" name="to_sent" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $msgRow['email'] ?>">
                            </div>


                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Your email:</label>
                                <input type="text" name="my_email" readonly class="form-control-plaintext" id="staticEmail" value="admin@gmail.com">
                            </div>


                            <div class="row">
                                <small class="text-red mb-2" style=" color:red"></small>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea name="message" class="form-control" id="message-text"></textarea>
                            </div>
                            <div class="row">
                                <small class="text-red mb-2 " style=" color:red"></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button name="sendMSG" type="submit" class="btn btn-primary">Send message</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No messages found.</p>
<?php endif; ?>
