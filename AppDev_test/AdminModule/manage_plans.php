<?php 
include('../config/connectDb.php');

// Function to add a new plan
function addPlan($name, $price, $interval, $interval_count) {
    global $conn;
    $query = "INSERT INTO plans (name, price, `interval`, interval_count) VALUES ('$name', $price, '$interval', $interval_count)";
    mysqli_query($conn, $query);
}

// Function to delete a plan
function deletePlan($id) {
    global $conn;
    
    // First, delete related rows from user_subscriptions table
    $query_delete_subscriptions = "DELETE FROM user_subscriptions WHERE plan_id = $id";
    mysqli_query($conn, $query_delete_subscriptions);

    // Then, delete the plan
    $query_delete_plan = "DELETE FROM plans WHERE id = $id";
    mysqli_query($conn, $query_delete_plan);
}

// Function to update a plan
function editPlan($id, $name, $price, $interval, $interval_count) {
    global $conn;
    $query = "UPDATE plans SET name = '$name', price = $price, `interval` = '$interval', interval_count = $interval_count WHERE id = $id";
    mysqli_query($conn, $query);
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

<div class="middle">
    <div class="container">
        <h1>Plan Details</h1>

        <!-- Display Table -->
        <table border="1" cellspacing="0" cellpadding="10" class="table table-striped">
            <tr class="text-center">
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Interval</th>
                <th>Interval Count</th>
            </tr>
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
        </table>
        <br>
        <a class="btn btn-success" href="index.php" style="margin-bottom: 20px;">Go Back</a>
    </div>
</div>

<div class="middle">
    <div class="container">
        <h1>Manage Plans</h1>

        <!-- Form for adding a new plan -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required><br>
            <label for="interval">Interval:</label>
            <select name="interval" id="interval" required>
                <option value="DAY">Day</option>
                <option value="WEEK">Week</option>
                <option value="MONTH">Month</option>
                <option value="YEAR">Year</option>
            </select><br>
            <label for="interval_count">Interval Count:</label>
            <input type="number" name="interval_count" id="interval_count" min="1" required><br>
            <button type="submit" class="btn btn-primary" name="add_plan">Add Plan</button>
        </form>

        <!-- Form for editing a plan -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="plan_id">Plan ID to Edit:</label>
            <input type="number" name="plan_id" id="plan_id" required><br>
            <label for="name">New Name:</label>
            <input type="text" name="name" id="name" required><br>
            <label for="price">New Price:</label>
            <input type="number" step="0.01" name="price" id="price" required><br>
            <label for="interval">New Interval:</label>
            <select name="interval" id="interval" required>
                <option value="DAY">Day</option>
                <option value="WEEK">Week</option>
                <option value="MONTH">Month</option>
                <option value="YEAR">Year</option>
            </select><br>
            <label for="interval_count">New Interval Count:</label>
            <input type="number" name="interval_count" id="interval_count" min="1" required><br>
            <button type="submit" class="btn btn-warning" name="edit_plan">Edit Plan</button>
        </form>

        <!-- Form for deleting a plan -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="plan_id">Plan ID to Delete:</label>
            <input type="number" name="plan_id" id="plan_id" required><br>
            <button type="submit" class="btn btn-danger" name="delete_plan">Delete Plan</button>
        </form>
        
        <br>
        <a class="btn btn-success" href="index.php" style="margin-bottom: 20px;">Go Back</a>
    </div>
</div>

<?php
include('../navbars/footer.php')
?>
