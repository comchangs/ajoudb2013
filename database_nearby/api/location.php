<?php
	/**
	 * Location API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 05
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$member_username = JMC_GetInput("member_username", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$mode = JMC_GetInput("mode", METHOD);
	$first = JMC_GetInput("first", METHOD);
	$limit = JMC_GetInput("limit", METHOD);
	$location_category_id = JMC_GetInput("location_category_id", METHOD);
	$location_latitude = JMC_GetInput("location_latitude", METHOD);
	$location_longitude = JMC_GetInput("location_longitude", METHOD);
	
	// Check variable
	if($first && $limit && $location_category_id && $location_latitude && $location_longitude) {
		try {
			// Select mode
			switch($mode) {
				default: {
					$data['process'] = false;
					$data['message'] = "Mode error";
					break;
				}
				case "list": {
					mssql_select_db(DB_NAME, $conn);
					$query = "select (*) 
					from location where location_category_id = '$location_category_id' order by distance DESC LIMIT $first , $limit";
					$dbraw = mssql_query($query, $conn);
					$result = mssql_fetch_array($dbraw);
					if($result['row'] > 0) {
						$i = 0;
						mssql_select_db(DB_NAME, $conn);
						$query2 = "select location_id, location_name, member_id, location_latitude, location_longitude, 
						Round(6371 * ASIN(SQRT(POWER(SIN(RADIANS($location_latitude – ABS(latitude))), 2) 
						+ COS(RADIANS($location_latitude)) * COS(RADIANS(ABS(latitude))) * POWER(SIN(RADIANS($location_longitude – longitude)), 2))), 1 ) AS distance 
						from location where location_category_id = '$location_category_id' order by distance DESC LIMIT $first , $limit";
						$dbraw2 = mssql_query($query2, $conn);
						while($result2 = mssql_fetch_array($dbraw2)) {
							unset($sub_data);
							$sub_data['location_id'] = $result2['location_id'];
							$sub_data['location_name'] = $result2['location_name'];
							$sub_data['member_id'] = $result2['member_id'];
							$sub_data['location_latitude'] = $result2['location_latitude'];
							$sub_data['location_longitude'] = $result2['location_longitude'];
							$sub_data['distance'] = $result2['distance'];
					
							$data[$i] = $sub_data;
							$i++;
						}
						JMC_PrintLIstJson('location', $data);
						exit();
					} else {
						$data['process'] = false;
						$data['message'] = "Not found location";
					}
					break;
				}
			
				case "write": {
					if($session && $member_username) {
						// Select DB table for event data
						mssql_select_db(DB_NAME, $conn);
						$query = "INSERT INTO user_location (user_location_type, user_location_name, user_location_latitude, user_location_longitude, user_location_regdate) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
						$dbraw = mssql_query($query, $conn);
						$data['process'] = true;
						$data['message'] = "Insert user_location";
					} else {
						$data['process'] = false;
						$data['message'] = "Session failed";
					}
					break;
				}
			}
		} catch(Exception $e) {
			$data['process'] = false;
			$data['message'] = $e;
		}
	} else {
		$data['process'] = false;
		$data['message'] = "Empty parameter";
	}
	JMC_PrintLIstJson('location', $data);
	    
    include_once("./include_db_disconnect.php");
?>