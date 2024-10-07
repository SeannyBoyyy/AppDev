<?php 
include('../config/connectDb.php');

$alertMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the action is for status change or editing subscription details
    if (isset($_POST['action']) && ($_POST['action'] == 'SUSPEND' || $_POST['action'] == 'ACTIVE' || $_POST['action'] == 'REJECT' || $_POST['action'] == 'EDIT')) {
        $id = $_POST['id'];
        
        if ($_POST['action'] == 'EDIT') {
            $valid_from = $_POST['valid_from'];
            $valid_to = $_POST['valid_to'];
            $paid_amount = $_POST['paid_amount'];
            $currency_code = $_POST['currency_code'];
            $created = $_POST['created'];
            $modified = $_POST['modified'];

            // Update the subscription details in the database
            $query = "UPDATE user_subscriptions SET 
                        valid_from = '$valid_from', 
                        valid_to = '$valid_to', 
                        paid_amount = '$paid_amount', 
                        currency_code = '$currency_code', 
                        created = '$created', 
                        modified = '$modified' 
                      WHERE id = $id";
        } else {
            $status = $_POST['action']; // Status to be changed
            
            // Update the status in the database
            $query = "UPDATE user_subscriptions SET status = '$status' WHERE id = $id";
        }

        if(mysqli_query($conn, $query)) {
            echo "
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Changes saved successfully.',
                        icon: 'success'
                    }).then(function() {
                        window.location = 'index.php?active=subscriber';
                    });
                </script>";
            exit();
        } else {
            $alertMessage = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="middle">
    <div class="container p-3">
        <h1>Manage User Subscriptions</h1>
        <br>
        <!-- Display Table for Active/Suspended Subscriptions -->
        <div class="table-responsive">
            <h2>Active/Suspended Subscriptions</h2>
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>User ID</th>
                        <th>Email</th> <!-- New Email Column -->
                        <th>Plan ID</th>
                        <th>Status</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    // Update query to join user_accounts table
                    $rows = mysqli_query($conn, "SELECT us.*, ua.email FROM user_subscriptions us 
                                                  JOIN user_accounts ua ON us.user_id = ua.id 
                                                  WHERE us.status != 'PENDING' 
                                                  ORDER BY us.id ASC");
                    foreach ($rows as $row) :
                    ?>
                        <tr class="text-center">
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["user_id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["email"]); ?></td> <!-- Display User Email -->
                            <td><?php echo $row["plan_id"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>
                            <td><?php echo $row["valid_from"]; ?></td>
                            <td><?php echo $row["valid_to"]; ?></td>
                            <td>
                                <!-- Form for status change -->
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary mb-2" name="action" value="SUSPEND">Suspend</button>
                                    <button type="submit" class="btn btn-success mb-2" name="action" value="ACTIVE">Active</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
        <!-- Display Table for Pending Subscriptions -->
        <div class="table-responsive">
            <h2>Pending Subscriptions</h2>
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>User ID</th>
                        <th>Phone Number</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rows = mysqli_query($conn, "SELECT us.*, ua.email FROM user_subscriptions us 
                                                  JOIN user_accounts ua ON us.user_id = ua.id 
                                                  WHERE us.status = 'PENDING' 
                                                  ORDER BY us.id ASC");
                    foreach ($rows as $row) :
                    ?>
                        <tr class="text-center">
                            <td><?php echo htmlspecialchars($row["id"]); ?></td>
                            <td><?php echo htmlspecialchars($row["user_id"]); ?></td>
                            <td><?php echo htmlspecialchars($row["subscriber_id"]); ?></td>
                            <td><?php echo htmlspecialchars($row["subscriber_name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["email"]); ?></td> <!-- Display User Email -->
                            <td><?php echo htmlspecialchars($row["status"]); ?></td>
                            <td>
                                <a href="../ProfileModule/img/<?php echo htmlspecialchars($row["image"]); ?>" target="_blank">
                                    <img src="../ProfileModule/img/<?php echo htmlspecialchars($row["image"]); ?>" alt="Receipt Image" width="100">
                                </a>
                            </td>
                            <td>
                                <!-- Form for status change -->
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-success mb-2" name="action" value="ACTIVE">Approve</button>
                                    <button type="submit" class="btn btn-danger mb-2" name="action" value="REJECT">Reject</button>
                                    <button type="button" class="btn btn-warning mb-2" onclick="document.getElementById('editModal<?php echo $row['id']; ?>').style.display='block'">Edit</button>
                                </form>

                                <!-- Edit Modal -->
                                <div id="editModal<?php echo $row['id']; ?>" class="modal">
                                    <div class="modal-content">
                                        <span class="close" onclick="document.getElementById('editModal<?php echo $row['id']; ?>').style.display='none'">&times;</span>
                                        <h2>Edit Subscription Details</h2>
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                            <div class="form-group">
                                                <label for="valid_from">Valid From:</label>
                                                <input type="datetime-local" class="form-control" id="valid_from" name="valid_from" value="<?php echo htmlspecialchars($row['valid_from']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="valid_to">Valid To:</label>
                                                <input type="datetime-local" class="form-control" id="valid_to" name="valid_to" value="<?php echo htmlspecialchars($row['valid_to']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="paid_amount">Paid Amount:</label>
                                                <input type="text" class="form-control" id="paid_amount" name="paid_amount" value="<?php echo htmlspecialchars($row['paid_amount']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="currency_code">Currency Code:</label>
                                                <input type="text" class="form-control" id="currency_code" name="currency_code" value="<?php echo htmlspecialchars($row['currency_code']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="created">Created:</label>
                                                <input type="datetime-local" class="form-control" id="created" name="created" value="<?php echo htmlspecialchars($row['created']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="modified">Modified:</label>
                                                <input type="datetime-local" class="form-control" id="modified" name="modified" value="<?php echo htmlspecialchars($row['modified']); ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="action" value="EDIT">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <style>
                            .modal {
                                display: none;
                                position: fixed;
                                z-index: 1;
                                left: 0;
                                top: 0;
                                width: 100%;
                                height: 100%;
                                overflow: auto;
                                background-color: rgb(0,0,0);
                                background-color: rgba(0,0,0,0.4);
                                padding-top: 60px;
                            }
                            .modal-content {
                                background-color: #fefefe;
                                margin: 5% auto;
                                padding: 20px;
                                border: 1px solid #888;
                                width: 80%;
                            }
                            .close {
                                color: #aaa;
                                float: right;
                                font-size: 28px;
                                font-weight: bold;
                            }
                            .close:hover,
                            .close:focus {
                                color: black;
                                text-decoration: none;
                                cursor: pointer;
                            }
                        </style>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
