<?php
class Cms_Rpc_Parameter
{
	protected $_server;
	protected $_message;
	
	protected $_mapper;
	
	public function __construct()
	{
		$this->_server = Zend_Registry::get('rpcServer');
		$this->_message = new Cms_Message();
	}
	
	protected function _parsePost( $post )
	{
		// parametry
		$data = array();
		foreach( $post as $param )
		{
			if( ($pos = strpos($param['name'], '[')) !== false ) {
				// tablica
				if( substr( $param['name'], $pos + 1) != ']' ) {
					$index = intval(substr( $param['name'], $pos + 1));
				}
				$param['name'] = substr( $param['name'], 0, $pos );
				if( !is_array($data[$param['name']]) ) {
					$data[$param['name']] = array();
				}
				if( isset($index) ) {
					$data[$param['name']][$index] = $param['value'];
				} else {
					$data[$param['name']][] = $param['value'];
				}
			}
			else
			{
				$data[$param['name']] = $param['value'];
			}
		}
		
		// opcje parametru
		if( is_array($data['option-value']) && is_array($data['option-id']) )
		{
			$data['optionsValues'] = array();
			foreach( $data['option-id'] as $index => $id )
			{
				if( !empty($data['option-value'][$index]) )
				{
					$data['optionsValues'][$index] = array(
						'id'	=> $id,
						'value' => $data['option-value'][$index]
					);
				}
			}
			unset($data['option-value']);
			unset($data['option-id']);
		}
		
		return $data;
	}
	
	public function add( $post )
	{
		$data = $this->_parsePost($post);

		$form = new Cms_Form_Product();
		$valid = $form->processAjax($data);
		if( $valid == 'true' )
		{
			// tworzymy parametr
			$className = 'Cms_Model_Item_Parameter_' . $data['type'];

			$parameter = new $className( $data );
			$mapper = new Cms_Db_Mapper_Item_Parameter();
			// zapisujemy
			if( $mapper->save($parameter) !== false )
			{
				$this->_message->success( 'Parametr "' . $parameter->getName() . '" zapisany' );
				return 'Parametr "' . $parameter->getName() . '" zapisany';
			}
			else
			{
				$this->_server->fault('Cos nie pojszlo', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
		}
		else
		{
			$this->_server->fault($valid, Zend_Json_Server_Error::ERROR_INTERNAL);
			return false;
		}
	}
	
	public function edit( $id, $post )
	{
		$data = $this->_parsePost($post);

		$form = new Cms_Form_Parameter();
		$valid = $form->processAjax($data);
		if( $valid == 'true' )
		{
			// wczytujemy parametr
			$parameter = Cms_Model_Item_Parameter::factory($id, $data);
			$mapper = new Cms_Db_Mapper_Item_Parameter();
			// zapisujemy
			if( $parameter->getId() && $mapper->save($parameter) !== false )
			{
				$this->_message->success( 'Parametr "' . $parameter->getName() . '" zapisany' );
				return 'Parametr "' . $parameter->getName() . '" zapisany';
			}
			else
			{
				$this->_server->fault('Cos nie pojszlo', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
		}
		else
		{
			$this->_server->fault($valid, Zend_Json_Server_Error::ERROR_INTERNAL);
			return false;
		}
	}
	
	public function delete( $id )
	{
		$this->_mapper()->find( $id, $product = new Cms_Model_Item_Product() );
		$this->_mapper()->delete( $product );
		//$this->_message->success('Artykuł "' . $article->getName() . '" usunięty');
		return 'Oferta "' . $product->getName() . '" usunięta';
	}
	
	protected function _mapper()
	{
		if( is_null($this->_mapper) )
		{
			$this->_mapper = new Cms_Db_Mapper_Item_Product();
		}
		return $this->_mapper;
	}
}