<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	function _initNameSpace(){
		Zend_Loader_Autoloader::getInstance()->registerNamespace("Mylib");
		Zend_Session::start();
	}
	
	function _initRoutes(){
		$router = Zend_Controller_Front::getInstance()->getRouter();
		$routesConfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/routes.xml');
		$router->addConfig($routesConfig, 'routes');
	}
		
	function _initAcl(){
		$helper= new Mylib_Controller_Helper_Acl();
		$helper->setRoles();
		$helper->setResources();
		$helper->setPrivilages();
		$helper->setAcl();
		
		$frontController = Zend_Controller_Front::getInstance();
		$frontController->registerPlugin(new Mylib_Controller_Plugin_Acl());
	}
	
	public function _initDoctrine() {
		$config = $this->getOption('doctrine');

		$this->getApplication()->getAutoloader()->pushAutoloader(array('Doctrine', 'autoload'));
		spl_autoload_register(array('Doctrine','modelsAutoload'));

		Doctrine_Core::setModelsDirectory($config['models_path']);

		Doctrine_Manager::connection($config['dsn'], 'app_con');		
		Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
		Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
		Doctrine_Core::loadModels($config['models_path']);
	}
	
	/**
	 * Use this function if you want to save the session in the db
	 */
	function ABANDON_initSession() {
		$this->bootstrap('db');
		$sessionConfig = array('name'=> 'website_session',
								'primary'=> array('sessionid','savepath','name'),
								'primaryAssignment' => array(
									'sessionId', //first column of the primary key is of the sessionID
									'sessionSavePath', //second column of the primary key is the save path
									'sessionName', //third column of the primary key is the session name
									),
								'modifiedColumn'=> 'modified',//time the session should expire
								'dataColumn'=> 'sessiondata', //serialized data
								'lifetimeColumn'=> 'lifetime',//end of life for a specific record
								);
		
		$savehandler = new Zend_Session_SaveHandler_DbTable($sessionConfig);
		$savehandler->setLifetime(60 * 60 * 24 * 30)->setOverrideLifetime(true); 
		Zend_Session::setSaveHandler($savehandler);
		Zend_Session::start();
	}

}
