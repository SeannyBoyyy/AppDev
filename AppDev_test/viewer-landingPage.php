<?php
    include('./navbars/farmer-navIndex.php');

    //to get all business profiles
    $farmsSql = 'SELECT id, name, text, image FROM business_profile';

    $farmsSqlRes = mysqli_query($conn, $farmsSql);

    $farms = mysqli_fetch_all($farmsSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($farmsSqlRes);


    //to get all posts from all farms
    $PostSql = 'SELECT name, text, image FROM posting_module';

    $postSqlRes = mysqli_query($conn, $PostSql);

    $profiles = mysqli_fetch_all($postSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($postSqlRes);

    

    //all advetisements
    $adsSQL = "SELECT * from business_advertisement";

    $adsRes = mysqli_query($conn, $adsSQL);

    $ads = mysqli_fetch_all($adsRes, MYSQLI_ASSOC);

    mysqli_free_result($adsRes);

    mysqli_close($conn);

?>

<div class="container-fluid justify-content-center align-items-center">
    <div class="container-fluid text-center mt-5 mb-3">
        <h1>Featured</h1>
        <div class="row">
            <div id="carouselExampleAutoplaying" class="carousel slide" style="height: 500px;" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach($ads as $key => $ad) { ?>
                        <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <img src="ProfileModule/img/<?php echo $ad['image']; ?>" class="img-fluid object-fit-contain">
                                </div>
                                <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                                    <div class="col-12 col-md-6 text-center">
                                        <h1><?php echo $ad['name']; ?></h1>
                                        <p class="text-center"><?php echo $ad['text']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>    
            </div>
            
        </div> 
    </div>       
    <div class="container text-center mt-5">
        <div class="row">
            <H1>Farms</H1>
            <?php foreach($farms as $farm){ ?>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card text-center overflow-auto" style="width: 300px; margin: auto; height: 500px;">
                    <img class="img-fluid img-thumbnail rounded-circle objext-fit-cover mx-auto d-block mt-5" src="ProfileModule/img/<?php echo $farm['image'] ?>" style="width: 150px; height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title"><?php echo $farm['name']?></h5>
                        <p class="card-text" style="height: 60px;"><?php echo $farm['text']?></p>
                        <a href="viewFarm.php?business_id=<?php echo $farm['id']; ?>" class="btn btn-primary">View Farm</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="container text-center mt-5">
        <div class="row">
            <H1>Products</H1>
            <?php foreach($profiles as $profile){ ?>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="card text-center overflow-auto" style="width: 300px; margin: auto; height: 500px;">
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
