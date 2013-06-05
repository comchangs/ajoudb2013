<?php
	/**
	 * Set include path
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2013. 06. 01 / Update: 2013. 06. 01
	 */

	define('_APP_PHPMODULE', true);
	
	// include basic variable and constant
	include_once("./include_system.php");
	
	// Include DB information
	include_once("./include_db.php");
	
	// Include common function
	include_once("./include_function.php");
?>