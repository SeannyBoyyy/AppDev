<?php
include('../config/connectDb.php');
include('../navbars/viewer-homepage.php');
$email = $password = $firstname = $lastname = $confirmPassword = '';
$errors = array('email'=>'', 'password'=>'', 'firstname'=>'', 'lastname'=>'', 'confirmPass'=>'');

if(isset($_POST['submit'])){
    // Email validation
    if(empty($_POST['email'])){
        $errors['email'] = 'E-mail is required!';
    } else {
        $email = $_POST['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'E-mail must be a valid email address';
        }
    }

    // Password validation
    if(empty($_POST['password'])){
        $errors['password'] = 'Password is required!';
    } else {
        $password = $_POST['password'];
        if(!preg_match('/^\S+$/', $password)){
            $errors['password'] = 'Password must not contain any spaces!';
        } 
        if(strlen($password) < 8){
            $errors['password'] = 'Password must be at least 8 characters long';
        }
        if (!preg_match("/[0-9]/", $password)) {
            $errors['password'] = 'Password must contain at least one number';
        }
        if ($_POST['password'] != $_POST['confirmPassword']) {
            $errors['confirmPass'] = 'Passwords do not match!';
        }
    }

    // First name validation
    if(empty($_POST['firstname'])){
        $errors['firstname'] = 'First name is required';
    } else {
        $firstname = $_POST['firstname'];
        if(!preg_match('/^[^0-9!@#$%^&*()_+=[\]{}|\\,.?: -]+$/', $firstname)){
            $errors['firstname'] = 'First name must not contain numbers and special characters';
        }
    }

    // Last name validation
    if(empty($_POST['lastname'])){
        $errors['lastname'] = 'Last name is required';
    } else {
        $lastname = $_POST['lastname'];
        if(!preg_match('/^[^0-9!@#$%^&*()_+=[\]{}|\\,.?: -]+$/', $lastname)){
            $errors['lastname'] = 'Last name must not contain numbers and special characters';
        }
    }

    if(empty($errors['email']) && empty($errors['password']) && empty($errors['firstname']) && empty($errors['lastname']) && empty($errors['confirmPass'])){
        // Check for duplicate email
        $query = "SELECT * FROM user_accounts WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $_POST['email']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) > 0){
            $errors['email'] = 'Email already exists';
        } else {
            // Insert the record
            $sql = "INSERT INTO user_accounts(email, passWord, firstName, lastName) VALUES(?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if($stmt){
                mysqli_stmt_bind_param($stmt, "ssss", $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['firstname'], $_POST['lastname']);
                if(mysqli_stmt_execute($stmt)){
                    header('Location: ./login-page.php');
                    exit(); // Exit to prevent further execution
                } else {
                    $errors['query_error'] = mysqli_stmt_error($stmt);
                }
            } else {
                $errors['query_error'] = mysqli_error($conn);
            }
        }
    }
}
?>

        <div class="container d-flex justify-content-center align-items-center min-vh-100 w75">
          <div class="row border rounded-5 p-3 bg-white shadow box-area">
          <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #90EE90;">
              <div class="featured-image mb-3">
               <img src="../img/1.png" class="img-fluid" style="width: 250px;">
              </div>
              <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">Promote Products.</p>
              <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Be one of our farmers on this platform.</small>
          </div> 
          <div class="col-md-6 right-box">
             <form class="row align-items-center" method="post" action="register-page.php" autocomplete="off">
                   <div class="header-text mb-2">
                        <h2>Hello!</h2>
                        <p>Sign Up to start promoting your products.</p>
                   </div>
                   <div class="input-group mb-1">
                       <input type="text" class="form-control form-control-lg bg-light fs-6 rounded" value="<?php echo htmlspecialchars($firstname)?>" placeholder="First Name" name="firstname">
                   </div>
                   <div class="row">
                   <small class="text-red mb-2 " style=" color:red"><?php echo $errors['firstname'] ?></small>
                   </div>
                   <div class="input-group mb-1">
                       <input type="text" class="form-control form-control-lg bg-light fs-6 rounded" value="<?php echo htmlspecialchars($lastname)?>" placeholder="Last Name" name="lastname">
                   </div>
                   <div class="row">
                   <small class="text-red mb-2 " style=" color:red"><?php echo $errors['lastname'] ?></small>
                   </div>
                   <div class="input-group mb-1">
                       <input type="text" class="form-control form-control-lg bg-light fs-6 rounded" value="<?php echo htmlspecialchars($email)?>" placeholder="Email" name="email">
                   </div>
                   <div class="row">
                   <small class="text-red mb-2 " style=" color:red"><?php echo $errors['email'] ?></small>
                   </div>
                   <div class="input-group mb-1">
                       <input type="password" class="form-control form-control-lg bg-light fs-6 rounded" value="<?php echo htmlspecialchars($password)?>" placeholder="Password" name="password">
                   </div>
                   <div class="row">
                   <small class="text-red mb-2 " style=" color:red"><?php echo $errors['password'] ?></small>
                   </div>
                   <div class="input-group mb-1">
                       <input type="password" class="form-control form-control-lg bg-light fs-6 rounded" placeholder="Confirm Password" value="<?php echo htmlspecialchars($confirmPassword)?>" name="confirmPassword">
                   </div>
                   <div class="row">
                   <small class="text-red mb-2 " style=" color:red"><?php echo $errors['confirmPass'] ?></small>
                   </div>
                   
                   <div class="input-group mb-3">
                       <button class="btn btn-lg w-100 fs-6" style="background-color: #90EE90;" name="submit" >Sign Up</button>
                   </div>
                   <div class="row">
                       <small><a href="./login-page.php" style="color: blue;">Already have an account?</a></small>
                   </div>
                   
             </form>
          </div> 
         </div>
       </div>
    <script
      defer=""
      src="https://unpkg.com/@teleporthq/teleport-custom-scripts"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
