<?php

class IndexController extends Zend_Controller_Action
{
	
    //protected $logger;
    public function init()
    {
        /* Initialize action controller here */
	// $this->logger = Mylib_LogFactory::getLogger();
    }

    public function indexAction()
    {
   	$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
        	$identity = $auth->getIdentity();
        	$this->view->userName = $identity['username'];
        	$this->view->firstName = $identity['firstname'];
        	$this->view->lastName = $identity['lastname'];
        	//$this->view->firstLogin = @($identity['username'] ? true : false);
        }
    }
        
    public function viewAction(){
    	$this->getHelper('viewRenderer')->setNoRender();
    	echo '<p>VIEW</p>';
   }
    
    public function editAction(){
    	$this->getHelper('viewRenderer')->setNoRender();
    	echo '<p>EDIT</p>';
    }

}
