<?php
include('../config/connectDb.php');
include('../navbars/profilepage-nav.php'); 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the record based on the provided id
    $result = mysqli_query($conn, "SELECT * FROM posting_module WHERE id = $id");
    $record = mysqli_fetch_assoc($result);

    if (!$record) {
        echo "Record not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Process the form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the submitted data
    $newName = mysqli_real_escape_string($conn, $_POST['new_name']);
    $newText = mysqli_real_escape_string($conn, $_POST['new_text']);
    $newCateg = mysqli_real_escape_string($conn, $_POST["new_Categ"]);
    $newPrice = mysqli_real_escape_string($conn, $_POST["new_price"]);
    // Process image upload if a new image is provided
    if ($_FILES['new_image']['error'] !== 4) {
        $fileName = $_FILES["new_image"]["name"];
        $fileSize = $_FILES["new_image"]["size"];
        $tmpName = $_FILES["new_image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, 'img/' . $newImageName);

            // Update the image filename in the database
            mysqli_query($conn, "UPDATE posting_module SET image = '$newImageName' WHERE id = $id");
        } else {
            echo "Invalid Image. Please upload a valid image file (jpg, jpeg, or png) with size up to 1MB.";
            exit();
        }
    }

    // Update the name and text in the database
    mysqli_query($conn, "UPDATE posting_module SET name = '$newName', text = '$newText', category = '$newCateg', price_range = '$newPrice' WHERE id = $id");

    // Redirect back to the profile-page.php page
    header("Location: profile-page.php?active=managePosts");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <div class="middle mt-5">
        <div class="container-fluid w-50" style="margin-top: 90px;">
            <div class="col-md-6 container-fluid text-center">
                <div class="container-fluid">
                <h2>Edit Record</h2>
                </div>
            </div>
        </div>
        <div class="container-sm d-flex align-items-center mt-5 border rounded-5 p-3 bg-white shadow box-area p-5">
            <!-- Edit Form -->
            <form action="" method="post" enctype="multipart/form-data" class="w-100 g-3">
                
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">New Name:</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="new_name" value="<?php echo $record['name']; ?>">
            </div>
            
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">New Text:</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="new_text" value="<?php echo $record['text']; ?>">
            </div>
    
            <div class="mb-3">
                <label for="category_product" class="form-label">Category</label>
                <select class="form-select" id="new_Categ" name="new_Categ">
                    <option value="<?php echo $record['category']; ?>"><?php echo $record['category']; ?></option>
                    <option value="Vegetables">Vegetables</option>
                    <option value="Fruits">Fruits</option>
                    <option value="Grains">Grains</option>
                    <option value="Dairy">Dairy</option>
                    <option value="Meat">Meat</option>
                    <option value="Fish">Fish</option>
                    <option value="Seafood">Seafood</option>
                    <option value="Farm Accessories">Farm Accessories</option>
                    <!-- Add more options as needed -->
                </select>         
            </div>
            
            <div class="mb-3">
                <label for="new_price" class="form-label">New Price:</label>
                <input type="text" class="form-control" id="new_price" name="new_price" value="<?php echo $record['price_range']; ?>">
            </div>

            <div class="mb-3">
            <label for="formFile" class="form-label">New Image:</label>
                <input type="file" id="formFile" class="form-control" name="new_image" accept=".jpg, .jpeg, .png" value="<?php echo $record['image']; ?>">
            </div>
                <button class="btn btn-success" type="submit">Save Changes</button>
            </form>
        </div>
        
    </div>
    
</body>
</html>
