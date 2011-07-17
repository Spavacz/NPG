<?php

class Cms_Rpc_Product
{

	/**
	 * @var Zend_Json_Server
	 */
	protected $_server;
	protected $_message;
	protected $_mapper;

	public function __construct()
	{
		$this->_server = Zend_Registry::get('rpcServer');
		$this->_message = new Cms_Message();
	}

	protected function _parsePost($post)
	{
		$data = array();
		foreach ($post as $param)
		{
			if (($pos = strpos($param['name'], '[')) !== false)
			{
				// tablica
				$name = substr($param['name'], 0, $pos);
				if (!isset($data[$name]) || !is_array($data[$name]))
				{
					$data[$name] = array();
				}

				if (strpos($param['name'], ']') > $pos + 1)
				{
					$index = intval(substr($param['name'], $pos + 1));
					$data[$name][$index] = $param['value'];
				}
				else
				{
					$data[$name][] = $param['value'];
				}
			}
			else
			{
				$data[$param['name']] = $param['value'];
			}
		}

		if (is_array($data['parameter']))
		{
			foreach ($data['parameter'] as $id => $value)
			{
				$data['parameters'][] = Cms_Model_Item_Parameter::factory($id, array('value' => $value));
			}
			unset($data['parameter']);
		}
		return $data;
	}

	public function add($post)
	{
		$data = $this->_parsePost($post);
		$form = new Cms_Form_Product();
		$valid = $form->processAjax($data);
		if ($valid == 'true')
		{
			$product = new Cms_Model_Item_Product($data);
			if ($this->_mapper()->save($product) === false)
			{
				$this->_server->fault('Cos nie pojszlo', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
			$this->_message->success('Oferta "' . $product->getName() . '" zapisana');
			return 'Oferta "' . $product->getName() . '" zapisana';
		}
		else
		{
			$this->_server->fault($valid, Zend_Json_Server_Error::ERROR_INTERNAL);
			return false;
		}
	}

	public function edit($id, $post)
	{
		$data = $this->_parsePost($post);
		$form = new Cms_Form_Product();
		$valid = $form->processAjax($data);
		if ($valid == 'true')
		{
			if (!$this->_mapper()->find($id, $product = new Cms_Model_Item_Product()))
			{
				$this->_server->fault('Product not found', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
			$product->setOptions($data);
			if ($this->_mapper()->save($product) === false)
			{
				$this->_server->fault('cos nie pojszlo', Zend_Json_Server_Error::ERROR_INTERNAL);
				return false;
			}
			$this->_message->success('Oferta "' . $product->getName() . '" zapisana');
			return 'Oferta "' . $product->getName() . '" zapisana';
		}
		else
		{
			$this->_server->fault($valid, Zend_Json_Server_Error::ERROR_INTERNAL);
			return false;
		}
	}

	public function delete($id)
	{
		$this->_mapper()->find($id, $product = new Cms_Model_Item_Product());
		$this->_mapper()->delete($product);
		//$this->_message->success('Artykuł "' . $article->getName() . '" usunięty');
		return 'Oferta "' . $product->getName() . '" usunięta';
	}

	/**
	 * @return Cms_Db_Mapper_Item_Product
	 */
	protected function _mapper()
	{
		if (is_null($this->_mapper))
		{
			$this->_mapper = new Cms_Db_Mapper_Item_Product();
		}
		return $this->_mapper;
	}

}