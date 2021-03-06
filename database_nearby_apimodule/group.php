<?php
	/**
	 * Group API
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
					case "total_list": {
						$query2 = "select count(*) as row from group";
						$dbraw2 = mysqli_query($conn, $query2);
						$result2 = mysqli_fetch_array($dbraw2, MYSQLI_ASSOC);
						if($result2['row'] > 0) {
							$i = 0;
							$query3 = "select * from group order by group_regdate desc";
							$dbraw3 = mysqli_query($conn, $query3);
							while($result3 = mysqli_fetch_array($dbraw2, MYSQLI_ASSOC)) {
								unset($sub_data);
								$sub_data['group_id'] = $result3['group_id'];
								$sub_data['group_name'] = $result3['group_name'];
								$sub_data['member_id'] = $result3['member_id'];
								$sub_data['group_description'] = $result3['group_description'];
								$sub_data['group_regdate'] = $result3['group_regdate'];
		
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('group', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found group";
						}
						break;
					}
					
					case "member_list": {
						$query2 = "select count(*) as row from group, group_member where group_member.group_id = group.group_id and group.group_id = ".$group_id;
						$dbraw2 = mysqli_query($conn, $query2);
						$result2 = mysqli_fetch_array($dbraw2, MYSQLI_ASSOC);
						if($result2['row'] > 0) {
							$i = 0;
							$query3 = "select * from group, group_member, member where group_member.group_id = group.group_id and group_member.group_id = ".$group_id;
							$dbraw3 = mysqli_query($conn, $query3);
							while($result3 = mysqli_fetch_array($dbraw3, MYSQLI_ASSOC)) {
								unset($sub_data);
								$sub_data['member_id'] = $result3['member_id'];
								$sub_data['member_username'] = $result3['group_name'];
								$sub_data['member_idm'] = $result3['member_id'];
								$sub_data['group_description'] = $result3['group_description'];
								$sub_data['group_regdate'] = $result3['group_regdate'];
					
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('group', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found group";
						}
						break;
					}
					
					case "my_list": {
						$query2 = "select count(*) as row from group, group_member where group_member.group_id = group.group_id and group_member.member_id = ".$member_id;
						$dbraw2 = mysqli_query($conn, $query2);
						$result2 = mysqli_fetch_array($dbraw2, MYSQLI_ASSOC);
						if($result2['row'] > 0) {
							$i = 0;
							$query3 = "select * from group, group_member, member where group_member.group_id = group.group_id and group_member.member_id = ".$member_id." order by group_member_joindate desc";
							$dbraw3 = mysqli_query($conn, $query3);
							while($result3 = mysqli_fetch_array($dbraw3, MYSQLI_ASSOC)) {
								unset($sub_data);
								$sub_data['group_id'] = $result3['group_id'];
								$sub_data['group_name'] = $result3['group_name'];
								$sub_data['member_id'] = $result3['member_id'];
								$sub_data['group_description'] = $result3['group_description'];
								$sub_data['group_member_joindate'] = $result3['group_member_joindate'];
									
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('group', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found group";
						}
						break;
					}
					
					
					case "create": {
						if($subject && $contents) {
							$query = "INSERT INTO document (document_regdate, document_moddate, document_title, document_contents, member_id) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
							$dbraw = mysqli_query($conn,$query);
							$data['process'] = true;
							$data['message'] = "Insert document";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					
					case "join": {
						if($subject && $contents) {
							$query = "INSERT INTO document (document_regdate, document_moddate, document_title, document_contents, member_id) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
							$dbraw = mysqli_query($conn,$query);
							$data['process'] = true;
							$data['message'] = "Insert document";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					
					case "cancel": {
						if($subject && $contents) {
							$query = "UPDATE document SET document_moddate = '$today', document_title = '$subject', document_contents = '$contents'";
							$dbraw = mysqli_query($conn,$query);
							$data['process'] = true;
							$data['message'] = "Update document";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					
					case "update": {
						if($subject && $contents) {
							$query = "UPDATE document SET document_moddate = '$today', document_title = '$subject', document_contents = '$contents'";
							$dbraw = mysqli_query($conn,$query);
							$data['process'] = true;
							$data['message'] = "Update document";
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
	JMC_PrintJson('board', $data);
	    
    include_once("./include_db_disconnect.php");
?>