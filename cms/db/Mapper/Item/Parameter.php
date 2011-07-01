<?php

class Cms_Db_Mapper_Item_Parameter extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Item_Parameters';
	protected $_modelName = 'Cms_Model_Item_Parameter';

	public function save(Cms_Model_Item_Parameter_Abstract $parameter)
	{
		$optionsTable = new Cms_Db_Table_Item_Parameters_Options();

		if (is_null($parameter->getId()))
		{
			$optionsOldRows = array();
		}
		else
		{
			$optionsOldRows = $optionsTable->fetchAll($this->__db()->quoteInto('idParameter = ?', $parameter->getId()))->toArray();
		}

		parent::save($parameter);

		// usuwam opcje
		$optionsToDelete = array_udiff($optionsOldRows, $parameter->getOptionsValues(), 'diff_options');

		foreach ($optionsToDelete as $option)
		{
			$optionsTable->delete($this->__db()->quoteInto('id = ?', $option['id']));
		}

		// aktualizuje opcje
		$optionsNew = array();
		foreach ($parameter->getOptionsValues() as $option)
		{
			if (empty($option['id']))
			{
				// nowa opcja
				$option['idParameter'] = $id;
				$optionsRow = $optionsTable->fetchNew();
				$optionsRow->value = $option['value'];
				$optionsRow->idParameter = $parameter->getId();
				$option['id'] = $optionsRow->save();
			}
			else
			{
				// aktualizacja opcji
				$optionsTable->update($option, $this->__db()->quoteInto('id = ?', $option['id']));
			}
			$optionsNew[] = $option;
		}
		$parameter->setOptionsValues($optionsNew);
		return $this;
	}

	public function find($id, &$parameter)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result))
		{
			return false;
		}
		$row = $result->current();
		$className = $this->_modelName . '_' . ucfirst($row->type);
		$parameter = new $className($row->toArray());
		return true;
	}

	public function fetchAll($where = null, $order = null, $limit = null,
		$page = null)
	{
		$select = $this->getDbTable()->select();

		if (!empty($where))
		{
			if(!is_array($where))
			{
				$where = array($where => '');
			}
			foreach($where as $cond => $param)
			{
				$select->where($cond, $param);
			}
		}
		
		if (!empty($order))
		{
			$select->order($order);
		}
		if (!empty($limit))
		{
			if (!empty($page))
			{
				$select->limitPage($page, $limit);
			}
			else
			{
				$select->limit($limit);
			}
		}
		$resultSet = $this->table()->fetchAll($select);


		$entries = array();
		foreach ($resultSet as $row)
		{
			$className = $this->_modelName . '_' . ucfirst($row->type);
			$entries[] = new $className($row->toArray());
		}
		return $entries;
	}

	public function fetchByItemId($itemId)
	{
		if (empty($itemId))
		{
			return array();
		}

		$select = $this->table()->select()
				->setIntegrityCheck(false)
				->from(array('ip' => 'items_parameters'))
				->join(array('pp' => 'products_parameters'), 'pp.idParameter = ip.id', array('value'))
				->where('pp.idProduct = ?', $itemId)
				->where('ip.status = 1');
		$result = $this->table()->fetchAll($select);
		$entries = array();
		foreach ($result as $row)
		{
			$className = $this->_modelName . '_' . ucfirst($row->type);
			$entries[] = new $className($row->toArray());
		}
		return $entries;
	}

	public function delete(Cms_Model_Item_Parameter_Abstract $parameter)
	{
		// option values
		$optionsTable = new Cms_Db_Table_Item_Parameters_Options();
		$where = $this->__db()->quoteInto('idParameter = ?', $parameter->getId());
		$optionsTable->delete($where);

		return parent::delete($parameter);
	}

}

/**
 * array_udiff helper
 */
function diff_options($a, $b)
{
	if ($a['id'] === $b['id'])
	{
		return 0;
	}
	return ($a['id'] > $b['id']) ? 1 : -1;
}
