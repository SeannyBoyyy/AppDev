<?php 
include('../config/connectDb.php');

// Function to add a new plan
function addPlan($name, $price, $interval, $interval_count) {
    global $conn;
    $query = "INSERT INTO plans (name, price, `interval`, interval_count) VALUES ('$name', $price, '$interval', $interval_count)";
    if(mysqli_query($conn, $query)) {
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Successfully added new plan.',
                    icon: 'success'
                }).then(function() {
                    window.location = 'index.php?active=plans';
                });
            </script>";
        
            exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Function to delete a plan
function deletePlan($id) {
    global $conn;
    
    // First, delete related rows from user_subscriptions table
    $query_delete_subscriptions = "DELETE FROM user_subscriptions WHERE plan_id = $id";
    mysqli_query($conn, $query_delete_subscriptions);

    // Then, delete the plan
    $query_delete_plan = "DELETE FROM plans WHERE id = $id";
    if(mysqli_query($conn, $query_delete_plan)) {
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Successfully deleted the plan.',
                    icon: 'success'
                }).then(function() {
                    window.location = 'index.php?active=plans';
                });
            </script>";
        
            exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Function to update a plan
function editPlan($id, $name, $price, $interval, $interval_count) {
    global $conn;
    $query = "UPDATE plans SET name = '$name', price = $price, `interval` = '$interval', interval_count = $interval_count WHERE id = $id";
    if(mysqli_query($conn, $query)) {
        echo "
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Successfully edited the plan.',
                    icon: 'success'
                }).then(function() {
                    window.location = 'index.php?active=plans';
                });
            </script>";
        
            exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Check if form is submitted for adding a plan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_plan'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $interval = $_POST['interval'];
    $interval_count = $_POST['interval_count'];
    addPlan($name, $price, $interval, $interval_count);
}

// Check if form is submitted for deleting a plan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_plan'])) {
    $id = $_POST['plan_id'];
    deletePlan($id);
}

// Check if form is submitted for editing a plan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_plan'])) {
    $id = $_POST['plan_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $interval = $_POST['interval'];
    $interval_count = $_POST['interval_count'];
    editPlan($id, $name, $price, $interval, $interval_count);
}

// Fetch all plans from the database
$query = "SELECT * FROM plans";
$result = mysqli_query($conn, $query);
?>

<style>
    input{
        margin-bottom: 15px;
    }
</style>

<div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Plan Details</h3>
                    </div>
                    <div class="card-body">
                        <!-- Display Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Interval</th>
                                        <th>Interval Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['price']; ?></td>
                                            <td><?php echo $row['interval']; ?></td>
                                            <td><?php echo $row['interval_count']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Plans</h3>
                    </div>
                    <div class="card-body">
                        <!-- Form for adding a new plan -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="interval">Interval:</label>
                                <select name="interval" id="interval" class="form-control" required>
                                    <option value="DAY">Day</option>
                                    <option value="WEEK">Week</option>
                                    <option value="MONTH">Month</option>
                                    <option value="YEAR">Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="interval_count">Interval Count:</label>
                                <input type="number" name="interval_count" id="interval_count" min="1" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_plan">Add Plan</button>
                        </form>
                        <hr>
                        <!-- Form for editing a plan -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="plan_id">Plan ID to Edit:</label>
                                <input type="number" name="plan_id" id="plan_id" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name">New Name:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="price">New Price:</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="interval">New Interval:</label>
                                <select name="interval" id="interval" class="form-control" required>
                                    <option value="DAY">Day</option>
                                    <option value="WEEK">Week</option>
                                    <option value="MONTH">Month</option>
                                    <option value="YEAR">Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="interval_count">New Interval Count:</label>
                                <input type="number" name="interval_count" id="interval_count" min="1" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning" name="edit_plan">Edit Plan</button>
                        </form>
                        <hr>
                        <!-- Form for deleting a plan -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="plan_id">Plan ID to Delete:</label>
                                <input type="number" name="plan_id" id="plan_id" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_plan">Delete Plan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>