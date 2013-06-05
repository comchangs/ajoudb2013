<?php
	/**
	 * Set environment
	 * 
	 * @author Jeong, Munchang
	 * @since  Create: 2012. 06. 30 / Update: 2012. 10. 05
	 */

	defined('_APP_PHPMODULE') or die('Access Error.');
	
	// Setup Path
	define("ADDRESS","http://dev.jwnc.net");
	define("APP_PHPMODULE_PATH","./ajoudb");
	
	// Define Method and Debug mode
	define("METHOD", "POST");
	define("DEBUG", true);
	
    // Set timezone
    putenv("TZ=Asia/Seoul");
    $today = date("Y-m-d H:i:s"); 
    $today_date = date("Y-m-d");
?>
