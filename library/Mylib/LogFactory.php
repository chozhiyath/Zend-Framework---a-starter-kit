<?php

/**
 * A general purpose log factory so it can be used anywhere in the web app
 */
class Mylib_LogFactory
{
	public static function getLogger()
	{
		$frontController = Zend_Controller_Front::getInstance();
		$bootstrap =  $frontController->getParam('bootstrap');
		if (!$bootstrap->hasPluginResource('log')) {
			return false;
		}
		$log = $bootstrap->getResource('log');
		return $log;
	}
}
