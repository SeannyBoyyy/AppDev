<?php
    include('./navbars/farmer-navIndex.php');

    //to get all business profiles
    $farmsSql = 'SELECT id, name, text, image FROM business_profile';

    $farmsSqlRes = mysqli_query($conn, $farmsSql);

    $farms = mysqli_fetch_all($farmsSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($farmsSqlRes);


    //to get all posts from all farms
    $PostSql = 'SELECT name, text, image, posted_by, category FROM posting_module';

    $postSqlRes = mysqli_query($conn, $PostSql);

    $profiles = mysqli_fetch_all($postSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($postSqlRes);

    $adsSQL = "SELECT * from business_advertisement";

    $adsRes = mysqli_query($conn, $adsSQL);

    $ads = mysqli_fetch_all($adsRes, MYSQLI_ASSOC);

    mysqli_free_result($adsRes);

    // mysqli_close($conn);


?>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .cardHover{
        transition: box-shadow 0.3s ease;
    }
    .cardHover:hover{
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .nav-link{
        color: white;
        background-color: #21D192;
    }
</style>
</head>
    <div class="container-fluid text-center">
        <div style="height: 50px; background-color:transparent;"></div>
        <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 60px;color:black;font-weight: bold;"><i class="fas fa-star"></i>Featured</h1>
        <div style="height: 50px; background-color:transparent;"></div>
        <div id="carouselExampleCaptions" class="carousel slide w-100" data-bs-ride="carousel" style="height: 500px;">
            <div class="carousel-indicators">
                <?php foreach($ads as $key => $ad) { ?>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo $key; ?>" class="<?php if($key === 0) echo 'active'; ?>" aria-current="true" aria-label="Slide <?php echo $key + 1; ?>"></button>
                <?php } ?>
            </div>
            <div class="carousel-inner">
                <?php foreach($ads as $key => $ad) { ?>
                    <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                        <img style="height: 500px;" src="ProfileModule/img/<?php echo $ad['image']; ?>" class="d-block w-100 object-fit-cover" alt="...">
                        <div class="carousel-caption align-items-start justify-content-start">
                            <h1 style="color:black;"><?php echo $ad['name']; ?></h1>
                            <p class="" style="color: black;"><?php echo $ad['text']; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="container-fluid text-center mt-5">
        <div class="row w-100">
            <h1 class="mt-5 mb-5" style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 60px;color:black;font-weight: bold;"><i class="fas fa-tractor"></i>Businesses</h1>
            <?php foreach($farms as $farm){ ?>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mx-auto mb-3" style="width: 300px;">
                <div class="card text-center cardHover" style="width: 300px; margin: auto; height: 450px;">
                    <img class="img-fluid img-thumbnail rounded-circle objext-fit-cover mx-auto d-block mt-5" src="ProfileModule/img/<?php echo $farm['image'] ?>" style="width: 150px; height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title"><?php echo $farm['name']?></h5>
                        <p class="card-text" style="height: 60px;"><?php echo $farm['text']?></p>
                        <a href="farm-viewFarm.php?business_id=<?php echo $farm['id']; ?>" class="btn btn-primary">View Farm</a>
                    </div>
                </div>
            </div>  
            <?php } ?>
        </div>
    </div>
    <div class="container-fluid text-center mt-5">
        <div class="row">
            <h1 class="mt-5 mb-5" style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 60px;color:black;font-weight: bold;"><i class="fas fa-shopping-basket"></i>Products</h1>
            <!-- Category Tabs -->
            <div class="col-12">
                <ul class="nav nav-pills justify-content-center mb-5" id="categoryTabs" role="tablist">
                    <?php 
                    // Query to get distinct categories from the database
                    $categoryQuery = "SELECT DISTINCT category FROM posting_module";
                    $categoryResult = mysqli_query($conn, $categoryQuery);
                    $firstCategory = true;
                    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) { ?>
                        <li class="nav-item" role="presentation">
                            <button class="categoryButton nav-link <?php if($firstCategory) echo 'active'; ?>" id="<?php echo $categoryRow['category']; ?>-tab" data-bs-toggle="pill" data-bs-target="#<?php echo $categoryRow['category']; ?>" type="button" role="tab" aria-controls="<?php echo $categoryRow['category']; ?>" aria-selected="<?php echo $firstCategory ? 'true' : 'false'; ?>"><?php echo $categoryRow['category']; ?></button>
                        </li>
                        <?php $firstCategory = false; ?>
                    <?php } ?>
                </ul>
                <div class="tab-content" id="categoryTabsContent">
                    <?php 
                    // Reset the category result
                    mysqli_data_seek($categoryResult, 0);
                    $firstCategory = true;
                    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) { ?>
                        <div class="tab-pane fade <?php if($firstCategory) echo 'show active'; ?>" id="<?php echo $categoryRow['category']; ?>" role="tabpanel" aria-labelledby="<?php echo $categoryRow['category']; ?>-tab">
                            <!-- Products of this category -->
                            <div class="row mx-auto">
                                <?php 
                                // Query to get products of this category
                                $categoryProductsQuery = "SELECT * FROM posting_module WHERE category = '{$categoryRow['category']}'";
                                $categoryProductsResult = mysqli_query($conn, $categoryProductsQuery);
                                while ($profile = mysqli_fetch_assoc($categoryProductsResult)) { ?>
                                    <div class=" grid-gap-0 col-12 col-md-6 col-lg-4 mx-auto mb-3" style="width: 300px;">
                                        <div class="card cardHover h-100 border-0 shadow-sm" style="width: 300px; margin: auto; height: 450px;">
                                            <img style="height: 300px;" class="object-fit-cover rounded-top-3" src="ProfileModule/img/<?php echo $profile['image'] ?>" class="card-img-top" alt="<?php echo $profile['name'] ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $profile['name']?></h5>
                                                <p class="card-text"><?php echo $profile['text']?></p>
                                                <p class="card-text">₱<?php echo $profile['price_range']?></p>
                                            </div>
                                            <div class="card-footer bg-transparent border-top-0">
                                                <a href="farm-viewFarm.php?business_id=<?php echo $profile['posted_by']; ?>" class="btn btn-primary">View Farm</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php $firstCategory = false; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    

    <div class="container mt-5">
		<div class="row">
			<div class="col">
				<div class="card" style="background-color: #ECEFF1;">
				    <div class="card-body">
                        <div class="row m-5">
                            <div class="col-12 p-6">
                                <h2 class="text-black">About Us:</h2>
                                <br>
                                <p class="text-black">At Zambales Local Market, we believe in the power of community 
                                    and the richness of our province's agricultural bounty. Our platform was born from 
                                    a shared vision to revolutionize the way local businesses connect with consumers, 
                                    breaking down barriers and creating opportunities for growth.
                                </p>
                                <p class="text-black">We understand the challenges faced by farmers and entrepreneurs in reaching wider markets 
                                    and promoting their products effectively. That's why we've built a user-friendly website 
                                    that serves as a virtual marketplace, where businesses can shine and consumers can discover 
                                    the diverse offerings of Zambales.
                                </p>
                                <p class="text-black">We understand the challenges faced by farmers and entrepreneurs in reaching wider markets 
                                    and promoting their products effectively. That's why we've built a user-friendly website 
                                    that serves as a virtual marketplace, where businesses can shine and consumers can discover 
                                    the diverse offerings of Zambales.
                                </p>
                                <p class="text-black">Our commitment goes beyond just facilitating transactions; we're dedicated 
                                    to fostering meaningful connections between producers and consumers, promoting transparency, 
                                    and sustainability every step of the way. With Zambales Local Market, you're not just buying products; 
                                    you're supporting a community, embracing quality, and championing the spirit of local entrepreneurship.
                                </p>
                                <p class="text-black">Join us on this journey as we celebrate the flavors, traditions, and stories of Zambales, 
                                    one click at a time.
                                </p>
                            </div>
                        </div>
                        <div class="row m-5">
                            <div class="col-6 p-6">
                                <h2 class="text-black">Vision Statement:</h2>
                                <br>
                                <p class="text-black">Empowering Zambales' local businesses to thrive in the digital age,
                                    fostering a sustainable economic ecosystem where every product tells a story of community, 
                                    quality, and connection.
                                </p>
                            </div>
                            <div class="col-6 p-6">
                                <h2 class="text-black">Mission Statement:</h2>
                                <br>
                                <p class="text-black">Our mission is to bridge the gap between Zambales' rich agricultural 
                                    heritage and the modern digital marketplace by providing a centralized platform for 
                                    local businesses to showcase their products. We strive to empower farmers and 
                                    entrepreneurs, enhance consumer access to fresh, locally sourced goods, and foster a 
                                    vibrant community of collaboration and sustainability.
                                </p>
                            </div>
                        </div>
                        <a href="" class="btn text-white ms-5 mb-3" style="background-color: #21D192">READ MORE</a>
                    </div>
				</div>
			</div>
		</div>
	</div>

<div style="height: 50px; background-color:transparent;"></div>
<?php include('./navbars/viewer-footer.php') ; ?>