<?php 
	error_reporting( E_ALL | E_STRICT );
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	
	define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));
	define('APPLICATION_ENV', 'loc');
	define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../../library'));
	
	$includePaths = array(LIBRARY_PATH, get_include_path());
	set_include_path(implode(PATH_SEPARATOR, $includePaths));
	
	require_once "Zend/Loader.php";
	require_once "Zend/Test/PHPUnit/ControllerTestCase.php";
	require_once 'PHPUnit/Framework/TestCase.php';
	require_once 'Zend/Application.php';
	
	Zend_Session::$_unitTestEnabled = true;
	Zend_Session::start();