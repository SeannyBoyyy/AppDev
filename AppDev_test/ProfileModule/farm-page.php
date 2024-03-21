<?php
    include('../config/connectDb.php');
    include('../navbars/viewer-homepage.php');
    session_start();
    if(isset($_SESSION['ownerID'])){
        $business_owner = $_SESSION['ownerID']; 
    }else{
        echo 'no owner ';
    }
    
        $getSql = "SELECT name, text, image FROM business_profile WHERE owner = ?";
        $stmt = mysqli_prepare($conn, $getSql);
        mysqli_stmt_bind_param($stmt, "i", $business_owner);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        if ($fromBusinessProfile = mysqli_fetch_assoc($result)) {
            $business_name = $fromBusinessProfile['name'];
            $business_bio = $fromBusinessProfile['text'];
            $business_pfp = $fromBusinessProfile['image'];
        } else {
            echo 'Failed to retrieve updated information or no data found';
            
        }

        $PostSql = 'SELECT name, text, image FROM posting_module';

        $postSqlRes = mysqli_query($conn, $PostSql);
    
        $profiles = mysqli_fetch_all($postSqlRes, MYSQLI_ASSOC);
    
        mysqli_free_result($postSqlRes);
?>


<div class="container-fluid mt-5">
    <div class="row justify-contents-center align-items-center">
        <div class="col-lg-4 col-12 text-center mb-3">
            <div class="">
                <?php
                  $res = mysqli_query($conn, "SELECT * FROM business_profile WHERE owner = $business_owner");
                  
                  while($row = mysqli_fetch_assoc($res)){
                ?>
                <img class="img-fluid img-thumbnail rounded-circle object-fit-cover mx-auto" style="height: 300px; width:300px;" src="img/<?php echo $row['image']; ?>">
                <?php } ?>
            </div>
            <h3 class="mt-3"><?php echo $business_name?></h3>
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="card-text"><?php
                        echo $business_bio
                    ?></p>
                    <h3>Location: kung meron</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-8 text-center col-sm-12">
                <H1>Products</H1>
                <?php foreach($profiles as $profile){ ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 mt-3">
                    <div class="card text-center p-3" style="width: 300px; margin: auto;">
                        <img class="img-fluid img-thumbnail rounded-circle objext-fit-cover mx-auto" src="img/<?php echo $profile['image'] ?>" style="width: 150px; height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title"><?php echo $profile['name']?></h5>
                            <p class="card-text" style="height: 60px;"><?php echo $profile['text']?></p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
        </div>
    </div>
</div>
<?php
    include('../navbars/footer.php');
?>
