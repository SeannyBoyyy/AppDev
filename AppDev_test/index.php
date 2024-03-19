<?php
    include('./navbars/farmer-navIndex.php');
    
    $PostSql = 'SELECT name, text, image FROM business_profile';

    $postSqlRes = mysqli_query($conn, $PostSql);

    $profiles = mysqli_fetch_all($postSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($postSqlRes);

    mysqli_close($conn);


?>

<div class="container-fluid justify-content-center align-items-center">
    <div class="container text-center mt-5">
        <div class="row">
            <div class="col-md-6 col-12">
                <img src="ProfileModule/img/65f9bb18db98b.png" class="img-fluid object-fit-contain">
            </div>
            <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                <p class="text-center">
                Step right up and discover the marvel of nature's candy - the magnificent mango!
                Bursting with vibrant colors and dripping with sweet, juicy goodness, this tropical treasure is more than just a fruit;
                it's a one-way ticket to paradise in every bite.
                </p>
            </div>
        </div>
    </div>
    <div class="container text-center mt-5">
        <div class="row">
            <?php foreach($profiles as $profile){ ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card w-100" style="width: 350px; margin: auto; height:500px;">
                    <img style="width: 300px; height: 300px;" class="img-fluid img-thumbnail rounded-circle object-fit-contain" src="ProfileModule/img/<?php echo $profile['image'] ?>">
                    <div class="card-body">
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
