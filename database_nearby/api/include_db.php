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
	$conn = mssql_connect(DB_SERVER, DB_USERID, ASSWORD) or die("Database connection error.");
?>