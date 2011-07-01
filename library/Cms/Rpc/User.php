<?php
class Cms_Rpc_User
{
	protected $_server;
	protected $_message;
	
	protected $_mapper;
	
	public function __construct()
	{
		$this->_server = Zend_Registry::get('rpcServer');
		$this->_message = new Cms_Message();
	}
	
	public function add( $data )
	{
		foreach( $data as $param )
		{
			$data[$param['name']] = $param['value'];
		}
		
		$form = new Cms_Form_User();
		$valid = $form->processAjax($data);
		if( $valid == 'true' )
		{
			$user = new Cms_Model_User( $data );
			if( $this->_mapper()->save($user) === false )
			{
				$this->_server->fault('Cos nie pojszlo', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
			$this->_message->success( 'Użytkownik "' . $user->getName() . ' ' . $user->getSurname(). '" zapisany' );
			return 'Użytkownik "' . $user->getName() . ' ' . $user->getSurname(). '" zapisany';
		}
		else
		{
			$this->_server->fault($valid, Zend_Json_Server_Error::ERROR_INTERNAL);
			return false;
		}
	}
	
	public function edit( $id, $data )
	{
		foreach( $data as $param )
		{
			$data[$param['name']] = $param['value'];
		}
		
		$form = new Cms_Form_User();
		$valid = $form->processAjax($data);
		if( $valid == 'true' )
		{
			if( !$this->_mapper()->find( $id, $user = new Cms_Model_User() ) )
			{
				$this->_server->fault('User not found', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
			$user->setOptions($data);
			if( $this->_mapper()->save($user) === false )
			{
				$this->_server->fault('cos nie pojszlo', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
			$this->_message->success( 'Użytkownik "' . $user->getName() . ' ' . $user->getSurname(). '" zapisany' );
			return 'Użytkownik "' . $user->getName() . ' ' . $user->getSurname(). '" zapisany';
		}
		else
		{
			$this->_server->fault($valid, Zend_Json_Server_Error::ERROR_INTERNAL);
			return false;
		}
	}
	
	public function delete( $id )
	{
		$this->_mapper()->find( $id, $user = new Cms_Model_User() );
		$this->_mapper()->delete( $user );
		return 'Użytkownik "' . $user->getName() . ' ' . $user->getSurname(). '" usunięty';
	}
	
	protected function _mapper()
	{
		if( is_null($this->_mapper) )
		{
			$this->_mapper = new Cms_Db_Mapper_User();
		}
		return $this->_mapper;
	}
}