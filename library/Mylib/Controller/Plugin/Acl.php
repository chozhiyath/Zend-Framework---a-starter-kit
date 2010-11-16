<?php
require_once 'Zend/Session/Namespace.php';

class Mylib_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
        	$identity = $auth->getIdentity();
            $role = $identity['role'];
        } else {
            $role = Mylib_Constants::$ROLE_GUEST;
        }
        
        
		$acl = Zend_Registry::get('acl');
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();
		$privilageName = $controllerName . '/' . $actionName;
		
		if(!$acl->isAllowed($role, null, $privilageName)){
			//save the request for redirecting after 
			//successful authorization/authentication			
			//$this->saveRequest($request);

			//rather, we take him to the homepage!
			$request->setControllerName('Index');
			$request->setActionName('index');
		}
	}
	
	/*
	private function saveRequest($request){
		$requestedController = $request->getControllerName();
		$requestedAction = $request->getActionName();
		$requestParams = $request->getParams();
		$savedRequest = array(
				'controller' => $requestedController,
				'action' => $requestedAction,
				'params' => $requestParams 
			);

		echo "SAVING... <br/>";
		echo "$requestedController **** <br/>";
		echo "$requestedAction **** <br/>";
		
		$session = new Zend_Session_Namespace('myapp');
		$session->data = $savedRequest;
	}
	*/
}
