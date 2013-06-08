<?php
/**
 * User location API
*
* @author Jeong, Munchang
* @since Create: 2013. 06. 01 / Update: 2013. 06. 05
*/

include_once("./include_setup.php");

// Input variable
$member_username = JMC_GetInput("member_username", METHOD);
$session = JMC_GetInput("session", METHOD);
$mode = JMC_GetInput("mode", METHOD);
$location_type = JMC_GetInput("location_type", METHOD);
$location_name = JMC_GetInput("location_name", METHOD);
$location_latitude = JMC_GetInput("location_latitude", METHOD);
$location_longitude = JMC_GetInput("location_longitude", METHOD);

// Check variable
if($session && $member_username) {
	try {
		// Select DB table for session ID
		$query = "SELECT member_id, session_id, member_username FROM member WHERE member_username = '$member_username'";
		$dbraw = mysqli_query($conn, $query);
		$result = mysqli_fetch_array($dbraw, MYSQLI_ASSOC);
		if(DEBUG) $data['session'] = $result['session_id'];

		// Authorize session ID
		if($result['session_id'] == $session) {

			$member_id = $result['member_id'];

			// Select mode
			switch($mode) {
				default: {
					$data['process'] = false;
					$data['message'] = "Mode error";
					break;
				}
				case "list": {
					if($member_id) {
						$query2 = "select count(*) as row from user_location where member_id = ".$member_id;
						$dbraw2 = mysqli_query($conn, $query2);
						$result2 = mysqli_fetch_array($dbraw2, MYSQLI_ASSOC);
						if($result2['row'] > 0) {
							$i = 0;
							$query3 = "select * from user_location where member_id = ".$member_id." order by user_location_regdate desc";
							$dbraw3 = mysqli_query($conn, $query3);
							while($result3 = mysqli_fetch_array($dbraw3, MYSQLI_ASSOC)) {
								unset($sub_data);
								$sub_data['user_location_id'] = $result3['user_location_id'];
								$sub_data['user_location_type'] = $result3['user_location_type'];
								$sub_data['user_location_name'] = $result3['user_location_name'];
								$sub_data['user_location_latitude'] = $result3['user_location_latitude'];
								$sub_data['user_location_longitude'] = $result3['user_location_longitude'];
								$sub_data['user_location_regdate'] = $result3['user_location_regdate'];
	
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('user_location', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found user_location";
						}
					} else {
						$data['process'] = false;
						$data['message'] = "Empty parameter";
					}
					break;
				}
				
				case "write": {
					if($subject && $contents) {
						$query = "INSERT INTO user_location (user_location_type, user_location_name, user_location_latitude, user_location_longitude, user_location_regdate) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
						$dbraw = mysqli_query($conn, $query);
						$data['process'] = true;
						$data['message'] = "Insert user_location";
					} else {
						$data['process'] = false;
						$data['message'] = "Empty parameter";
					}
					break;
				}
					
				case "update": {
					if($subject && $contents) {
						$query = "UPDATE user_location SET document_moddate = '$today', document_title = '$subject', document_contents = '$contents'";
						$dbraw = mysqli_query($conn, $query);
						$data['process'] = true;
						$data['message'] = "Update user_location";
					} else {
						$data['process'] = false;
						$data['message'] = "Empty parameter";
					}
					break;
				}
			}
		} else {
			$data['process'] = false;
			$data['message'] = "Session failed";
		}
	} catch(Exception $e) {
		$data['process'] = false;
		$data['message'] = $e;
	}
} else {
	$data['process'] = false;
	$data['message'] = "Empty parameter";
}
JMC_PrintJson('user_location', $data);
 
include_once("./include_db_disconnect.php");
?>