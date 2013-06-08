<?php
	/**
	 * DB connector
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 05
	 */

	defined('_APP_PHPMODULE') or die('Access Error.');
	
	// Input burnaby information
	define("DB_SERVER", "localhost");
	define("DB_USERID", "ajoudb");
	define("PASSWORD", "ajoudb");
	define("DB_NAME", "ajoudb");
	
	// Generate connection variable
	$conn = mysqli_connect(DB_SERVER, DB_USERID, PASSWORD, DB_NAME);
	
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	
?>