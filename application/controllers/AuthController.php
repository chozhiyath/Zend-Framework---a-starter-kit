<?php
 
/**
 * AuthController 
 */
class AuthController extends Zend_Controller_Action{

    public function init(){
	$this->getHelper('viewRenderer')->setNoRender();
    }

	public function loginAction(){
    	$auth = Zend_Auth::getInstance();
    	
    	$username = $this->getRequest()->getParam("username");
    	$password = $this->getRequest()->getParam("password");
    	$authAdapter = new Mylib_AuthAdapter($username, $password);
    	
    	// Attempt authentication, saving the result
	$result = $auth->authenticate($authAdapter);
	if (!$result->isValid()) {
		echo '<p>INVALID</p>';
	}else{
		Zend_Session::rememberMe(60*60*24*30);
		echo '<p>VALID</p>';
		$this->_redirect('/');
	}
    }
    
    public function logoutAction()
    {
    	//Remove the authenticated user session
	$auth = Zend_Auth::getInstance();
	$auth->clearIdentity();
	$this->_redirect("/");
    }
	
}
