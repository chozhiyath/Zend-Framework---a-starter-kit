<?php
class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase{
	
	public function setUp(){
		$this->bootstrap = new Zend_Application(
        	'testing',
			APPLICATION_PATH . '/configs/application.xml'
		);
		
		parent::setUp();
	}
	
	public function testIndexAction(){
		$this->request->setMethod('GET');
		$this->dispatch('/');
		$this->assertController('index');
		$this->assertAction('index');
		$this->assertNotRedirect();
		$this->assertQueryContentContains('h1', 'Welcome to');		
	}
}
