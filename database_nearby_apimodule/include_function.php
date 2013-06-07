<?php
	/**
	 * Function
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 05
	 */
	
	defined('_APP_PHPMODULE') or die('Access Error.');
	
	/**
	 * @author Jeong, Munchang
	 * JMC_PrintJson: Generate sigle array type of json data
	 * In: $dataname - Name of data, $data - Array type data
	 * Out: None
	 */
	function JMC_PrintJson($dataname, $data) {
		echo "{ "."\"".$dataname."\" : [";		
		echo json_encode($data);
		echo "] }";
	}
	
	/**
	 * @author Jeong, Munchang
	 * JMC_PrintListJson: Generate multi array type of json data
	 * In: $dataname - Name of data, $data - Array type data
	 * Out: None
	 */
	function JMC_PrintListJson($dataname, $data) {
		echo "{ "."\"".$dataname."\" : [";
		for($i = 0; $i < count($data); $i++) {
			echo (($i != 0) ? "," : "");
			echo json_encode($data[$i]);
		}
		echo "] }";
	}
	
	/**
	 * @author Jeong, Munchang
	 * JMC_CreateSession: Generate session ID - Mix data and randum number and encode to base64 code
	 * In: none
	 * Out: $new_session
	 */
	function JMC_CreateSession() {
		$new_session = base64_encode(date('y').mt_rand(10, 99).date('m').mt_rand(10, 99).date('d').mt_rand(10, 99).date('h').mt_rand(10, 99).date('i').mt_rand(10, 99).date('s').mt_rand(10, 99));
		return $new_session;
	}
	
	/**
	 * @author Jeong, Munchang
	 * JMC_GetInput: Inistailize input variable
	 * In: $name - Name of variable, $method - Type of method(GET, POST)
	 * Out: $result - string or false
	 */
	function JMC_GetInput($name, $method) {
		if($name) {
			if($method == "POST") {
				if(isset($_POST[$name])) {
					$result = htmlspecialchars($_POST[$name]);
				}
			} elseif ($method == "GET") {
				if(isset($_GET[$name])) {
					$result = htmlspecialchars($_GET[$name]);
				}
			}
			if ($result == "" || $result == " ") {
				return false;
			} else {
				return $result;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * Page_View1
	 * In: $cpage1, $scale1
	 * Out: $start_q1
	 */
	function Page_View1($cpage1, $scale1)
	{
		$start_q1 = $scale1 * ($cpage1 - 1);
		return $start_q1;
	}
	
	/*
	 * Br_wordcut
	 * In: $String, $MaxLen, $ShortenStr)
	 * Out: $news_textt
	 */
	function Br_wordcut($String, $MaxLen, $ShortenStr="..")
	{
		$news_textt = $String;
		$str = $news_textt;
		//
		if(strlen($str) > $MaxLen)
		{
			//$str = preg_replace("/\s+/", ' ', preg_replace("/(\r\n|\r|\n)/", " ", $str));
			$str = preg_replace("/(\r\n|\r|\n)/", " ", $str);
			//
			if(strlen($str) >= $MaxLen)
			{
				//$words=explode(' ',preg_replace("/(\r\n|\r|\n)/"," ",$str));
				$words = preg_split('/( |-|=|_|,|\.)/i', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
	
				$str = '';
				$i = 0;
				while(strlen($str) + strlen($words[$i]) < $MaxLen)
				{
	
					$str.=$words[$i];
					$i++;
				}
				//
				$news_textt = trim($str);
				$news_textt .= "..";
	
			}
		}
		return $news_textt;
	}
?>