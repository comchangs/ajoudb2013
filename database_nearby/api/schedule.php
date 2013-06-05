<?php
	/**
	 * Board API
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 05
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
	if($session && $id) {
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
						$query2 = "select count(*) as row from document where board_id = ".$board_id;
						$dbraw2 = mssql_query($query2, $conn);
						$result2 = mssql_fetch_array($dbraw2);
						if($result2['row'] > 0) {
							$i = 0;
							mssql_select_db(DB_NAME, $conn);
							$query3 = "select * from document where board_id = ".$board_id." order by regdate desc";
							$dbraw3 = mssql_query($query3, $conn);
							while($result3 = mssql_fetch_array($dbraw3)) {
								unset($sub_data);
								$sub_data['document_id'] = $result3['qna_id'];
								$sub_data['document_regdate'] = $result3['regDate'];
								$sub_data['document_moddate'] = $result3['answerDate'];
								$sub_data['document_title'] = $result3['document_title'];
								$sub_data['member_id'] = $result3['member_id'];
		
								$data[$i] = $sub_data;
								$i++;
							}
							JMC_PrintLIstJson('board', $data);
							exit();
						} else {
							$data['process'] = false;
							$data['message'] = "Not found document";
						}
						break;
					}
					case "view": {
						// Check variable
						if($view_id) {
							// Select DB table for evnent data
							mssql_select_db(DB_NAME, $conn);
							$query = "select * from document where document_id = '$document_id'";
							$dbraw = mssql_query($query, $conn);
							$result = mssql_fetch_array($dbraw);
							$data['document_id'] = $result3['qna_id'];
							$data['document_regdate'] = $result3['regDate'];
							$data['document_moddate'] = $result3['answerDate'];
							$data['document_title'] = $result3['document_title'];
							$data['document_contents'] = $result3['document_contents'];
							$data['member_id'] = $result3['member_id'];
							$data['process'] = true;
							$data['message'] = "Select document";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					case "write": {
						if($subject && $contents) {
							// Select DB table for event data
							mssql_select_db(DB_NAME, $conn);
							$query = "INSERT INTO document (document_regdate, document_moddate, document_title, document_contents, member_id) VALUES ('$today', '$today', '$document_title', '$document_contents', $member_id)";
							$dbraw = mssql_query($query, $conn);
							$data['process'] = true;
							$data['message'] = "Insert document";
						} else {
							$data['process'] = false;
							$data['message'] = "Empty parameter";
						}
						break;
					}
					
					case "update": {
						if($subject && $contents) {
							// Select DB table for event data
							mssql_select_db(DB_NAME, $conn);
							$query = "UPDATE document SET document_moddate = '$today', document_title = '$subject', document_contents = '$contents'";
							$dbraw = mssql_query($query, $conn);
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