<?php
class Mylib_AuthAdapter implements Zend_Auth_Adapter_Interface
{
	
	private $username;
	private $password;
	
	/**
	* Sets username and password for authentication
	*
	* @return void
	*/
	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;		
	}
	
	/**
	* Performs an authentication attempt
	*
	* @throws Zend_Auth_Adapter_Exception If authentication cannot
	*         be performed
	* @return Zend_Auth_Result
	*/
	public function authenticate()
	{
		//TODO This is just a mock adapter, this will be changed accordingly after fixing the db schema
		if( !isset($this->username) 
			|| !isset($this->password) 
			|| empty($this->username) 
			|| empty($this->password) ){
        	return new Zend_Auth_Result(
        		Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
				array(),
				array('Invalid Authorization'));
		}

		//Check for the validity of credentials
		$userTable = Doctrine_Core::getTable('User');
		$entity = $userTable->findOneByUsernameAndPasswordAndStatus($this->username, md5($this->password), Mylib_Constants::$STATUS_ACTIVE);
	
		if(isSet($entity) && !empty($entity)){
			$identity = array(
					'username' => $this->username,
					'firstname' => $entity->firstname,
					'lastname' => $entity->lastname,
					'role' => $entity->user_role
			);
			
			/*
			 *TODO will use this later
			if($entity->firstLogin)
				$identity['firstLogin'] = true;
			*/

			//Update last logged in time
			$q = Doctrine_Query::create()
					->update('User')
					->set('last_logged_in', '?', date('Y-m-d H:i:s'))
					->where('id = ?', $entity->id)
					->execute();

			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $identity, array());	
		}

		return new Zend_Auth_Result(
			Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
			array(),
			array('Invalid Authorization'));
	}
}
