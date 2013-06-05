<?php

require_once "setting.php";

$date = date('Y-m-d H:i:s');

$db = new mysqli('localhost', $dbuser, $dbpasswd, $dbname);
if (mysqli_connect_errno())
{
	echo 'Error: Could not connect to database. Please try again later.';
	exit;
}

if(isset($_GET['first'])) {
	$first = $_GET['first'];
} else {
	$first = "0";
}


if(isset($_GET['limit'])) {
	$limit = $_GET['limit'];
} else {
	$limit = "10";
}

if(isset($_GET['location_category'])) {
	$location_category = $_GET['location_category'];
} else {
	$location_category = "";
}

if(isset($_GET['location_latitude'])) {
	$location_latitude = $_GET['location_latitude'];
} else {
	$location_latitude = "";
}

if(isset($_GET['location_longitude'])) {
	$location_longitude = $_GET['location_longitude'];
} else {
	$location_longitude = "";
}

$query = "select location_id, location_name, member_id, location_latitude, location_longitude, Round(6371 * ASIN(SQRT(POWER(SIN(RADIANS($location_latitude – ABS(latitude))), 2) + 
COS(RADIANS($location_latitude)) * COS(RADIANS(ABS(latitude))) * POWER(SIN(RADIANS($location_longitude – longitude)), 2))), 1 ) 
AS distance from location where location_category = '$location_category' order by distance DESC LIMIT $first , $limit"; //퀴리(SQL)문 작성
$result_contents = mysqli_query($db, $query); //쿼리 실행

echo "{ "."\"location\" : [";
while($row = mysqli_fetch_array($result_contents)) {
	echo json_encode($row);
}
echo "] }";
?>