<?php
	if(!isset($_SESSION)) { session_start(); }
    /***************************************************************************************************
	 * Change this to point to where these files are stored in your document root directory. Leave as '/'
	 * if files are in document root
	 * *************************************************************************************************/
	$logout_path = '/series/dynamic/am_production/'; /*change here*/
	$_SESSION['include_path'] = $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/am_production/';/*change here*/
	$_SESSION['link_root'] = '/series/dynamic/am_production/';/*change here*/

?>