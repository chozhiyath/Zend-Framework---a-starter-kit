<?php
class Mylib_Controller_Helper_Acl
{
	//TODO roles and resources will be changed, this is just a hello world implementation of acl
	public $acl;
	public function __construct()
	{
		$this->acl = new Zend_Acl();
	}
	
	public function setRoles()
	{
		$guest = new Zend_Acl_Role(Mylib_Constants::$ROLE_GUEST);
		$this->acl->addRole($guest);
		$this->acl->addRole(new Zend_Acl_Role(Mylib_Constants::$ROLE_USER), $guest);
		$this->acl->addRole(new Zend_Acl_Role(Mylib_Constants::$ROLE_ADMIN));

	}

	public function setResources()
	{
	}

	public function setPrivilages()
	{
		$this->acl->allow(Mylib_Constants::$ROLE_GUEST);
		$this->acl->deny(Mylib_Constants::$ROLE_GUEST, null, array(
						'index/edit',
					 	'index/delete'						
					));

		$this->acl->allow(Mylib_Constants::$ROLE_USER);
		$this->acl->allow(Mylib_Constants::$ROLE_ADMIN);
	}
	
	public function setAcl()
	{
		Zend_Registry::set('acl', $this->acl);
	}
	
}
