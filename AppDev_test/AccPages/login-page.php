<?php
include('../navbars/viewer-homepage.php');
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

        $sql = "SELECT email, passWord, id FROM user_accounts WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
       
        if($row = mysqli_fetch_assoc($result)) {
          if(password_verify($password, $row['passWord'])) {
              session_start();
              $sql = "SELECT name, text, image FROM business_profile WHERE owner = ?";
              $stmt = mysqli_prepare($conn, $sql);
              mysqli_stmt_bind_param($stmt, "i", $row['id']);
              mysqli_stmt_execute($stmt);
              $_SESSION['ownerID'] = $row['id'];
              
              $sqlResult = mysqli_stmt_get_result($stmt);
              if ($businessRow = mysqli_fetch_assoc($sqlResult)) {
                $_SESSION['business_name'] = $businessRow['name'];
                $_SESSION['bio'] = $businessRow['text'];
                $_SESSION['pfp'] = $businessRow['image'];
                
                header('Location: ../ProfileModule/profile-page.php'); 
                exit();
            }else{
              header('Location: ../ProfileModule/profile-setup.php');
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

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="row border rounded-5 p-3 bg-white shadow box-area">
    <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #90EE90;">
      <div class="featured-image mb-3">
        <img src="../img/1.png" class="img-fluid" style="width: 250px;">
      </div>
      <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">Promote Products.</p>
      <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Be one of our farmers on this platform.</small>
    </div> 
    <div class="col-md-6 right-box">
      <form class="row align-items-center" method="post" action="login-page.php" autocomplete="off">
        <div class="header-text mb-4">
          <h2>Hello,Again</h2>
          <p>We are happy to have you back.</p>
        </div>
        <div class="input-group mb-1">
          <input type="text" class="form-control form-control-lg bg-light fs-6 rounded" name="email" value="<?php echo htmlspecialchars($email)?>" placeholder="Email address">
        </div>
        <div class="row">
          <small class="text-red mb-2 " style=" color:red"><?php echo $errors['email'] ?></small>
        </div>
        <div class="input-group mb-1">
          <input type="password" class="form-control form-control-lg bg-light fs-6 rounded" name="password" placeholder="Password" value="<?php echo htmlspecialchars($password)?>">  
        </div>
        <div class="row">
          <small class="text-red mb-2" style=" color:red"><?php echo $errors['password'] ?></small>
        </div>
        <div class="input-group mb-3">
          <button class="btn btn-lg  w-100 fs-6" name="submit" style="background-color: #90EE90;" data-bs-toggle="modal" data-bs-target="#exampleModal">Login</button>
        <div class="row">
          <small style="color: blue;">Don't have account? <a href="./register-page.php">Sign Up</a></small>
        </div>
      </form>
    </div> 
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
