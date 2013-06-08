<?php
	/**
	 * Member API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 06
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$mode = JMC_GetInput("mode", METHOD);
	$push_id = JMC_GetInput("push_id", METHOD);
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$username = JMC_GetInput("username", METHOD);
	$password = JMC_GetInput("password", METHOD);
	$type = JMC_GetInput("type", METHOD);
	$phonetype = JMC_GetInput("phonetype", METHOD);
	$pushtype = JMC_GetInput("pushtype", METHOD);
	
	// Select mode
	switch($mode) {
		default: {
			$data['process'] = false;
			$data['message'] = "Mode error";
			break;
		}
		case "check_push_id": {
			// Check variable
			if($push_id && $username && $phonetype) {
				try {
					// Select DB table for push ID
					$new_session = JMC_CreateSession();
					$query = "SELECT member_phone_type, member_push_id, session_id FROM member WHERE username = '$username'";
					$dbraw = mysqli_query($conn, $query);
					$result = mysqli_fetch_array($dbraw);
						
					if($result['member_push_id'] == $push_id) {
						$data['process'] = true;
						$data['message'] = "Registered user";
					} elseif (isset($result['member_push_id']) && $result['member_push_id'] != $push_id) {
						$query = "UPDATE member SET member_push_id = '$push_id', session_id = '$new_session', member_phone_type = '$phonetype' WHERE username = '$username'";
						$dbraw = mysqli_query($conn, $query);
						$data['process'] = true;
						$data['message'] = "User's push_id updated";
					}
					$query = "SELECT session_id FROM member WHERE member_username = '$username'";
					$dbraw = mysqli_query($conn, $query);
					$result = mysqli_fetch_array($dbraw);
					$data['session'] = $result['session_id'];
		
				} catch(Exception $e) {
					$data['process'] = false;
					$data['message'] = $e;
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Empty parameter";
			}
			break;
		}
		case "join": {			
			// Check variable
			if($push_id && $username && $password && $tel && $type) {
				try {
					// Select DB table for push ID
					$new_session = JMC_CreateSession();
					$query = "SELECT username FROM member WHERE username = '$username'";
					$dbraw = mysqli_query($conn, $query);
					$result = mysqli_fetch_array($dbraw);
					
					if($result['username'] != $username) {
						$query = "INSERT INTO member (member_tel_number, member_push_id, member_regDate, member_modDate, session_id, member_phone_type) VALUES ('$tel', '$push_id', '$today', '$today', '$new_session', $phonetype)";
						$dbraw = mysqli_query($conn, $query);
						
						$query = "SELECT session_id FROM member WHERE member_username = '$username'";
						$dbraw = mysqli_query($conn, $query);
						$result = mysqli_fetch_array($dbraw);
						
						$data['process'] = true;
						$data['message'] = "User Inserted";
						$data['session'] = $result['session_id'];
					} else {
						$data['process'] = false;
						$data['message'] = "Duplicated username";
					}
					 
				} catch(Exception $e) {
					$data['process'] = false;
					$data['message'] = $e;
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Empty parameter";
			}
			break;
		}
		
		case "update": {
			
			// Check variable
			if($session && $username && $password && $tel) {
				try {
					// Select DB table for session ID
					$query = "SELECT session_id FROM member WHERE member_username = '$username'";
					$dbraw = mysqli_query($conn, $query);
					$result = mysqlu_fetch_array($dbraw);
					if(DEBUG) $data['session'] = $result['session_id'];
					
					// Authorize session ID
					if($result['session_id'] == $session) {
						$query = "UPDATE member SET member_tel_number = '$tel', member_password = '$password' WHERE member_username = '$username'";
						$dbraw = mysqli_query($conn, $query);
						$data['process'] = true;
						$data['message'] = "User data updated";
					}
					else {
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
			break;
		}
		case "login": {

			// Check variable
			if($username && $password) {
				try {
					// Select DB table for authorize session ID and telephone number
					$new_session = JMC_CreateSession();
					$query = "SELECT member_username FROM member WHERE member_username = '$username' and member_password = '$password'";
					$dbraw = mtsqli_query($conn, $query);
					$result = mysqli_fetch_array($dbraw);
					if(isset($result['member_username'])) {
						$query = "UPDATE member SET session_id = '$new_session' WHERE member_username = '$username'";
						$dbraw = mysqli_query($conn, $query);
						sleep(1); //DB 업데이트 되는 시간
						$query = "SELECT session_id, member_type FROM member WHERE member_username = '$username'";
						$dbraw = mysqli_query($conn, $query);
						$result = mysqli_fetch_array($dbraw);
						if($new_session == $result['session_id']) {
							$data['session'] = $result['session_id'];
							$data['type'] = $result['member_type'];
							$data['process'] = true;
							$data['message'] = "Created new session ID";
						} else {
							$data['process'] = false;
							$data['message'] = "Not created new session ID";
						}
					} else {
						$data['process'] = false;
						$data['message'] = "Not found member data";
					}
				} catch(Exception $e) {
					$data['process'] = false;
					$data['message'] = $e;
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Empty parameter";
			}
			break;
		}
	}
	JMC_PrintJson('login', $data);
	
	//finish DB connection.
	include_once("./include_db_disconnect.php");
?>