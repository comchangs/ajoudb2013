<?php
	/**
	 * Schedule API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 06
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$member_username = JMC_GetInput("member_username", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$mode = JMC_GetInput("mode", METHOD);
	$subject = JMC_GetInput("subject", METHOD);
	$contents = JMC_GetInput("contents", METHOD);
	$document_id = JMC_GetInput("document_id", METHOD);
	$board_id = JMC_GetInput("board_id", METHOD);
	
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
					$query = "select (*) 
					from schedule where location_category_id = '$location_category_id' order by distance DESC LIMIT $first , $limit";
					$dbraw = mysqli_query($conn, $query);
					$result = mysqli_fetch_array($dbraw, MYSQLI_ASSOC);
					if($result['row'] > 0) {
						$i = 0;
						$query2 = "select location_id, location_name, member_id, location_latitude, location_longitude, 
						Round(6371 * ASIN(SQRT(POWER(SIN(RADIANS($location_latitude – ABS(latitude))), 2) 
						+ COS(RADIANS($location_latitude)) * COS(RADIANS(ABS(latitude))) * POWER(SIN(RADIANS($location_longitude – longitude)), 2))), 1 ) AS distance 
						from schedule where location_category_id = '$location_category_id' order by distance DESC LIMIT $first , $limit";
						$dbraw2 = mysqli_query($conn, $query2);
						while($result2 = mysqli_fetch_array($dbraw2, MYSQLI_ASSOC)) {
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
						JMC_PrintLIstJson('schedule', $data);
						exit();
					} else {
						$data['process'] = false;
						$data['message'] = "Not found schedule";
					}
					break;
				}
			
				case "write": {
					if($session && $member_username) {
						// Select DB table for session ID
						$query = "SELECT member_id, session_id, member_username FROM member WHERE member_username = '$member_username'";
						$dbraw = mysqli_query($conn, $query);
						$result = mysqli_fetch_array($dbraw, MYSQLI_ASSOC);
						if(DEBUG) $data['session'] = $result['session_id'];
						
						// Authorize session ID
						if($result['session_id'] == $session) {
						
							$member_id = $result['member_id'];
							// Select DB table for event data
							$query = "INSERT INTO schedule (user_location_type, user_location_name, user_location_latitude, user_location_longitude, user_location_regdate) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
							$dbraw = mysqli_query($conn, $query);
							$data['process'] = true;
							$data['message'] = "Insert schedule";
						} else {
							$data['process'] = false;
							$data['message'] = "Session failed";
						}
					} else {
						$data['process'] = false;
						$data['message'] = "Empty parameter";
					}
					break;
				}
				
				case "update": {
					if($session && $member_username) {
						// Select DB table for session ID
						$query = "SELECT member_id, session_id, member_username FROM member WHERE member_username = '$member_username'";
						$dbraw = mysqli_query($conn, $query);
						$result = mysqli_fetch_array($dbraw, MYSQLI_ASSOC);
						if(DEBUG) $data['session'] = $result['session_id'];
				
						// Authorize session ID
						if($result['session_id'] == $session) {
				
							$member_id = $result['member_id'];
							// Select DB table for event data
							$query = "INSERT INTO schedule (user_location_type, user_location_name, user_location_latitude, user_location_longitude, user_location_regdate) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
							$dbraw = mysqli_query($conn, $query);
							$data['process'] = true;
							$data['message'] = "Insert schedule";
						} else {
							$data['process'] = false;
							$data['message'] = "Session failed";
						}
					} else {
						$data['process'] = false;
						$data['message'] = "Empty parameter";
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
	JMC_PrintLIstJson('schedule', $data);
	    
    include_once("./include_db_disconnect.php");
?>