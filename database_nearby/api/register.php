<?php
	/**
	 * Register API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 06
	 */

	include_once("./include_setup.php");
	
	// Input variable
	$member_username = JMC_GetInput("member_username", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$mode = JMC_GetInput("mode", METHOD);
	$location_id = JMC_GetInput("location_id", METHOD);
	$register_status = JMC_GetInput("register_status", METHOD);
	$register_id = JMC_GetInput("register_id", METHOD);
	
	// Check variable
	if($session && $member_username) {
		try {
			// Select DB table for session ID
			mssql_select_db(DB_NAME, $conn);
			$query = "SELECT member_id, session_id, member_username FROM member WHERE member_username = '$member_username'";
			$dbraw = mssql_query($query);
			$result = mssql_fetch_array($dbraw);
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
						mssql_select_db(DB_NAME, $conn);
						$query2 = "select count(*) as row from register, location, location_category where register.location_id = location.location_id and location_category.location_category_id = location.location_category_id and member_id = ".$member_id;
						$dbraw2 = mssql_query($query2, $conn);
						$result2 = mssql_fetch_array($dbraw2);
						if($result2['row'] > 0) {
							$i = 0;
							mssql_select_db(DB_NAME, $conn);
							$query3 = "select * from register, location, location_category where register.location_id = location.location_id and location_category.location_category_id = location.location_category_id and member_id = ".$member_id." order by register_regdate desc";
							$dbraw3 = mssql_query($query3, $conn);
							while($result3 = mssql_fetch_array($dbraw3)) {
								unset($sub_data);
								
								$sub_data['register_id'] = $result3['register_id'];
								$sub_data['location_id'] = $result3['location_id'];
								$sub_data['location_name'] = $result3['location_name'];
								$sub_data['location_category_name'] = $result3['location_category_name'];
		
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('register', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found data";
						}
						break;
					}

					case "apply": {
						if($location_id && $member_id) {
							// Select DB table for event data
							mssql_select_db(DB_NAME, $conn);
							$query = "INSERT INTO register (register_regdate, location_id, member_id) VALUES ('$today', '$location_id', '$member_id')";
							$dbraw = mssql_query($query, $conn);
							$data['process'] = true;
							$data['message'] = "Insert data";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					
					case "update": {
						if($register_id && $register_status) {
							// Select DB table for event data
							mssql_select_db(DB_NAME, $conn);
							$query = "UPDATE register SET register_status = '$register_status' where register_id=".$register_id;
							$dbraw = mssql_query($query, $conn);
							$data['process'] = true;
							$data['message'] = "Update data";
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
	JMC_PrintJson('register', $data);
	    
    include_once("./include_db_disconnect.php");
?>