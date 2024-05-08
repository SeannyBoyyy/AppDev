<?php
include('../navbars/login-homepage.php');
include('../config/connectDb.php');
$email = $password = '';
$errors = array('email'=>'', 'password'=>'');

if(isset($_POST['submit'])){
    if(empty($_POST['email'])){
        $errors['email'] = 'E-mail is required! <br />';
    } else {
        $email = $_POST['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'E-mail must be a valid email address';
        }
    }

    if(empty($_POST['password'])){
        $errors['password'] = 'Password is required! <br />';
    } else {
        $password = $_POST['password'];
        if(!preg_match('/^\S+$/', $password)){
            $errors['password'] = 'Password must not contain any spaces!';
        }
        if(strlen($password) < 8){
            $errors['password'] = 'Password must be at least 8 characters ';
        }
        if (!preg_match("/[0-9]/", $password)) {
            $errors['password'] = 'Password must contain at least one number';
        }
    }
    
    if(empty($errors['email']) && empty($errors['password'])){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if user is admin
        if ($email === 'admin@gmail.com' && $password === 'Admin1234') {
          // Start admin session
          session_start();
          $_SESSION['admin_email'] = $email;

          header('Location: ../AdminModule/index.php?active=dashboard');
          exit();
        }

        // Regular user login
        $sql = "SELECT ua.id AS account_id, ua.subscription_id, ua.passWord, us.status 
                FROM user_accounts AS ua 
                LEFT JOIN user_subscriptions AS us ON ua.subscription_id = us.id 
                WHERE ua.email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        
        if($row = mysqli_fetch_assoc($result)) {
          if(password_verify($password, $row['passWord'])) {

              if($row['status'] === 'SUSPEND') {
                session_start();
                $_SESSION['acc_info'] = $row['account_id']; // suspend acc

                unset($_SESSION['ownerID']);
                // Redirect suspended account to suspend.php
                header('Location: ../AccPages/suspend.php');
                exit();
              }

              session_start();
              $_SESSION['ownerID'] = $row['account_id']; // Change to 'ownerID'

              if ($row['status'] !== 'ACTIVE') {
                header('Location: ../payment/index.php');
                exit();
              }

              
              
              // Check if profile setup is complete
              $profile_sql = "SELECT id FROM business_profile WHERE owner = ?";
              $profile_stmt = mysqli_prepare($conn, $profile_sql);
              mysqli_stmt_bind_param($profile_stmt, "i", $_SESSION['ownerID']); // Use 'ownerID' session variable
              mysqli_stmt_execute($profile_stmt);
              $profile_result = mysqli_stmt_get_result($profile_stmt);

              if ($profile_row = mysqli_fetch_assoc($profile_result)) {
                // Profile setup complete, redirect to profile page
                header('Location: ../ProfileModule/profile-page.php?active=profile'); 
                exit();
              } else {
                // Profile setup incomplete, redirect to profile setup
                header('Location: ../ProfileModule/profile-setup.php');
                exit();
              }
          } else {
              $errors['password'] = 'Invalid e-mail or password';
          }
      } else {
          $errors['password'] = 'Invalid e-mail or password';
      }
  }
} 
?>

<head>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    .ui {
      background: transparent;
      border-radius: 15px;
      width: 100%; /* Start at full width */
      max-width: 500px; /* Maximum width */
      height: 525px;
    }
    .text-center{
      width: 350px;
      margin-left: auto; /* Center the input group horizontally on larger screens */
      margin-right: auto;
    }
    .input-group {
      width: 100%; /* Start at full width */
      max-width: 350px; /* Maximum width */
      height: 55px;
      border: none;
      outline: none;
      margin-left: auto; /* Center the input group horizontally on larger screens */
      margin-right: auto;
    }

    .input-group i {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
    }

        @media (min-width: 576px) {
          .container {
              width: 500px;
          }
        }
  </style>
</head>
<body style="background-image: url('http://localhost/AppDev/AppDev_test/ProfileModule/img/R16731_product.jpg');background-size: cover; background-repeat: no-repeat;">
<div class="container d-flex justify-content-center align-items-center min-vh-80" style="margin-top: 100px; max-width: 500px; width: 100%;">
  <div class="row border rounded-5 p-3 shadow box-area w-100 mb-5" style="width: 600px; margin-left: auto; margin-right: auto;background-color: rgba(255, 255, 255, 0.6);">
    <div class="ui">
      <div class="col-md-6 right-box w-100 " style="margin-top:30px;">
        <form class="row align-items-center" method="post" action="login-page.php" autocomplete="off">
          <div class="header-text mb-4">
            <h2 style="margin-left:30px;font-family:monospace">Hello,Again</h2>
            <p style="margin-left:30px;font-family:monospace">We are happy to have you back.</p>
            <h1 style="margin-left:50px;margin-top:50px;font-family:monospace">Login</h1>
          </div>
          <div class="input-group mb-1">
            <input type="text" class="form-control form-control-lg bg-light fs-6 rounded" name="email" value="<?php echo htmlspecialchars($email)?>" placeholder="Email address"><i class='bx bxs-envelope'></i>
          </div>
          <div class="row text-center">
            <small class="mb-2" style=" color:red"><?php echo $errors['email'] ?></small>
          </div>
          <div class="input-group mb-1">
            <input type="password" class="form-control form-control-lg bg-light fs-6 rounded" name="password" placeholder="Password" value="<?php echo htmlspecialchars($password)?>"><i class='bx bxs-lock-alt'></i> 
          </div>
          <div class="row text-center">
            <small class="mb-2" style="color:red;"><?php echo $errors['password'] ?></small>
          </div>
          <div class="input-group mb-3">
            <button class="btn btn-lg  w-100 fs-6" name="submit" style="background-color: transparent; border:2px solid black; border-radius: 15px;margin-top:10px;" data-bs-toggle="modal" data-bs-target="#exampleModal">Login</button>
          <div class="row text-center">
            <small style="color: blue;">Don't have account? <a href="./register-page.php">Sign Up</a></small>
          </div>
        </form>
      </div> 
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
