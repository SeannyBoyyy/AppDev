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
<style>
    /* Set width for the first span */
    .card-body span:first-child {
        width: 250px; 
        display: inline-flex;
        margin-right: 100px;
    }
    
    
    .card-body span:nth-child(2) {
        display: inline-flex; 
        text-align: center;
        width: 250px;
    }
    .card-body{
        transition: box-shadow 0.3s ease;
    }
    .card-body:hover{
      color: black;
      transform: translateY(-5px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    .modal-backdrop {
        display: none !important;
    }

</style>
<div class="col-12 d-flex align-items-center border mt-5 border-0 rounded-5 p-3 bg-white box-area">
    <?php if (!empty($msgRows)): ?>
        <div class="row d-block align-items-start justify-content-start w-100 mx-auto">
            <h1 class="fw-bold">Messages</h1>
            <?php foreach ($msgRows as $index => $msgRow): ?>
                <div class="container-fluid" data-bs-toggle="modal" data-bs-target="#viewMessageModal<?php echo $index ?>">
                    <div class="row g-0">
                        <div class="card w-100 p-0 mx-1 my-1 border-0">
                            <div class="card-body w-100 p-2 border-bottom my-0">
                                <span class="p-0 overflow-hidden"><?php echo $msgRow['email'] ?></span> 
                                <span class="card-text overflow-hidden"><?php echo $msgRow['subject'] ?>.</span> 
                                <span class="card-text overflow-hidden"><?php echo $msgRow['message'] ?>.</span> 
                                <span class="float-end"><?php echo $msgRow['created_at'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- view message modal -->
                <div class="modal fade" id="viewMessageModal<?php echo $index ?>" tabindex="-1" aria-labelledby="viewMessageModalLabel<?php echo $index ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="viewMessageModalLabel<?php echo $index ?>">Message Details</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="staticEmail" class="col-form-label">Sent By:</label>
                                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $msgRow['email'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="msgSubject" class="col-form-label">Sent By:</label>
                                    <input type="text" readonly class="form-control-plaintext" id="msgSubject" value="<?php echo $msgRow['subject'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Message:</label>
                                    <textarea readonly class="form-control" id="message-text"><?php echo $msgRow['message'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="message-time" class="col-form-label">Sent At:</label>
                                    <input type="text" readonly class="form-control-plaintext" id="message-time" value="<?php echo $msgRow['created_at'] ?>">
                                </div>
                                <hr>
                                <h1 class="modal-title fs-5" id="replyMessageModalLabel<?php echo $index ?>">Reply</h1>
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
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</div>
