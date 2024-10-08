<?php

//submit_rating.php
$connect = new PDO("mysql:host=localhost;dbname=product_promotion", "dan", "test1234");

$business_id = $_GET['business_id'];

if(isset($_POST["rating_data"]))
{

	// Check if status is set and valid
    if(isset($_POST['status']) && ($_POST['status'] == 'APPROVE' || $_POST['status'] == 'DENY')) {
        $status = $_POST['status'];
    } else {
        $status = 'PENDING'; // Default status
    }

    $data = array(
        ':user_name'    =>  $_POST["user_name"],
        ':business_id'  =>  $_POST["business_id"], 
        ':user_rating'  =>  $_POST["rating_data"],
        ':user_review'  =>  $_POST["user_review"],
        ':datetime'     =>  time(),
        ':status'       =>  $status // Include status in data array
    );

    $query = "
    INSERT INTO review_table  
    (user_name, business_id, user_rating, user_review, datetime, status) 
    VALUES (:user_name, :business_id, :user_rating, :user_review, :datetime, :status)
	";

    $statement = $connect->prepare($query);

    $statement->execute($data);

}

if(isset($_POST["action"]))
{	
	$average_rating = 0;
	$total_review = 0;
	$five_star_review = 0;
	$four_star_review = 0;
	$three_star_review = 0;
	$two_star_review = 0;
	$one_star_review = 0;
	$total_user_rating = 0;
	$review_content = array();

	$query = "
	SELECT * FROM review_table
    WHERE business_id = $business_id AND status = 'APPROVE'
    ORDER BY review_id DESC
	";

	$result = $connect->query($query, PDO::FETCH_ASSOC);

	foreach($result as $row)
	{
		$review_content[] = array(
			'user_name'		=>	$row["user_name"],
			'business_id'	=>	$row["business_id"],
			'user_review'	=>	$row["user_review"],
			'rating'		=>	$row["user_rating"],
			'datetime'		=>	date('l jS, F Y h:i:s A', $row["datetime"])
		);

		if($row["user_rating"] == '5')
		{
			$five_star_review++;
		}

		if($row["user_rating"] == '4')
		{
			$four_star_review++;
		}

		if($row["user_rating"] == '3')
		{
			$three_star_review++;
		}

		if($row["user_rating"] == '2')
		{
			$two_star_review++;
		}

		if($row["user_rating"] == '1')
		{
			$one_star_review++;
		}

		$total_review++;

		$total_user_rating = $total_user_rating + $row["user_rating"];

	}

	$average_rating = $total_user_rating / $total_review;

	$output = array(
		'average_rating'	=>	number_format($average_rating, 1),
		'total_review'		=>	$total_review,
		'five_star_review'	=>	$five_star_review,
		'four_star_review'	=>	$four_star_review,
		'three_star_review'	=>	$three_star_review,
		'two_star_review'	=>	$two_star_review,
		'one_star_review'	=>	$one_star_review,
		'review_data'		=>	$review_content
	);

	echo json_encode($output);

}

?>