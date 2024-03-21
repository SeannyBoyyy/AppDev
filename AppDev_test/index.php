<?php
    include('./navbars/farmer-navIndex.php');
    //to get all business profiles
    $farmsSql = 'SELECT name, text, image FROM business_profile';

    $farmsSqlRes = mysqli_query($conn, $farmsSql);

    $farms = mysqli_fetch_all($farmsSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($farmsSqlRes);


    //to get all posts from all farms
    $PostSql = 'SELECT name, text, image FROM posting_module';

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
                <div class="col-12 col-md-6 text-center">
                    <h1>Mango</h1>
                    <p class="text-center">
                    Step right up and discover the marvel of nature's candy - the magnificent mango!
                    Bursting with vibrant colors and dripping with sweet, juicy goodness, this tropical treasure is more than just a fruit;
                    it's a one-way ticket to paradise in every bite.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container text-center mt-5">
        <div class="row">
            <H1>Farms</H1>
            <?php foreach($farms as $farm){ ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center" style="width: 300px; margin: auto; height: 500px;">
                    <img class="img-fluid img-thumbnail rounded-circle objext-fit-cover mx-auto d-block mt-5" src="ProfileModule/img/<?php echo $farm['image'] ?>" style="width: 150px; height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title"><?php echo $farm['name']?></h5>
                        <p class="card-text" style="height: 60px;"><?php echo $farm['text']?></p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="container text-center mt-5">
        <div class="row">
            <H1>Producs</H1>
            <?php foreach($profiles as $profile){ ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center" style="width: 300px; margin: auto; height: 500px;">
                    <img class="img-fluid img-thumbnail rounded-circle objext-fit-cover mx-auto d-block mt-5" src="ProfileModule/img/<?php echo $profile['image'] ?>" style="width: 150px; height: 150px;">
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
