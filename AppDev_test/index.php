<?php
    include('./navbars/farmer-navIndex.php');

    //to get all business profiles
    $farmsSql = 'SELECT id, name, text, image FROM business_profile';

    $farmsSqlRes = mysqli_query($conn, $farmsSql);

    $farms = mysqli_fetch_all($farmsSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($farmsSqlRes);


    //to get all posts from all farms
    $PostSql = 'SELECT name, text, image, posted_by FROM posting_module';

    $postSqlRes = mysqli_query($conn, $PostSql);

    $profiles = mysqli_fetch_all($postSqlRes, MYSQLI_ASSOC);

    mysqli_free_result($postSqlRes);

    $adsSQL = "SELECT * from business_advertisement";

    $adsRes = mysqli_query($conn, $adsSQL);

    $ads = mysqli_fetch_all($adsRes, MYSQLI_ASSOC);

    mysqli_free_result($adsRes);

    mysqli_close($conn);


?>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .fas{
        margin-right: 20px; /* Right margin for spacing */
    }
</style>
</head>

<div class="container">
    <div class="container text-center">
    <div style="height: 50px; background-color:transparent;"></div>
            <h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 60px;color:white;font-weight: bold;"><i class="fas fa-star"></i>Featured</h1><div style="height: 50px; background-color:transparent;"></div>
            <div class="row">
                <div id="carouselExampleAutoplaying" class="carousel carousel-info slide"  data-bs-ride="carousel" style="width: 1500px; height: 500px;margin-left: auto;margin-right: auto;">
                    <div class="carousel-inner ">
                        <?php foreach($ads as $key => $ad) { ?>
                            <div class="carousel-item <?php if($key === 0) echo 'active'; ?>">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <img style="height: 450px; width: 450px;" src="ProfileModule/img/<?php echo $ad['image']; ?>" class="img-fluid object-fit-contain">
                                    </div>
                                    <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                                        <div class="col-12 col-md-6 text-center">
                                            <h1 style="color:black;"><?php echo $ad['name']; ?></h1>
                                            <p class="text-center" style="color: black;"><?php echo $ad['text']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev"style="width:80px;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next"style="width:80px;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>    
                </div>
                
            </div> 
    </div>
    <div class="container text-center mt-5">
        <div class="row">
            <H1 class="mt-5 mb-5" style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 60px;color:white;font-weight: bold;"><i class="fas fa-tractor"></i>Farms</H1>
            <?php foreach($farms as $farm){ ?>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="card text-center" style="width: 300px; margin: auto; height: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); border-radius: 20px;">
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
    <div class="container text-center mt-5">
        <div class="row">
            <H1 class="mt-5 mb-5" style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 60px;color:white;font-weight: bold;"><i class="fas fa-shopping-basket"></i>Products</H1>
            <?php foreach($profiles as $profile){ ?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="card text-center" style="width: 300px; margin: auto; height: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); border-radius: 20px;">
                    <img class="img-fluid img-thumbnail rounded-circle objext-fit-cover mx-auto d-block mt-5" src="ProfileModule/img/<?php echo $profile['image'] ?>" style="width: 150px; height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h5 class="card-title"><?php echo $profile['name']?></h5>
                        <p class="card-text" style="height: 60px;"><?php echo $profile['text']?></p>
                        <a href="farm-viewFarm.php?business_id=<?php echo $profile['posted_by']; ?>" class="btn btn-primary">View Farm</a>
                    </div>
                </div>
            </div>
            <?php } ?>
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