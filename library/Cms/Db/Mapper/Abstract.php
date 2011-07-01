<?php

/**
 * abstrakcyjna klasa mappera
 *
 * @author Spav
 */
abstract class Cms_Db_Mapper_Abstract
{

	protected $_dbTable;
	protected $_dbTableName;
	protected $_modelName;

	public function __db()
	{
		return $this->getDbTable()->getAdapter();
	}

	public function setDbTable($dbTable)
	{
		if (is_string($dbTable))
		{
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract)
		{
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 * Tablica DB
	 * @return Zend_Db_Table
	 */
	public function getDbTable()
	{
		if (null === $this->_dbTable)
		{
			$this->setDbTable($this->_dbTableName);
		}
		return $this->_dbTable;
	}

	/**
	 * Aliad do getDbTable
	 * return Zend_Db_Table
	 */
	public function table()
	{
		return $this->getDbTable();
	}

	/**
	 * Ustawia szukany obiekt
	 * 
	 * @param int $id
	 * @param Cms_Model $model
	 * @return bool 
	 */
	public function find($id, Cms_Model $model)
	{
		if (ctype_digit($id) || is_int($id))
		{
			$result = $this->table()->find($id);
		}
		else
		{
			$result = $this->table()->fetchAll($id);
		}
		if (0 == count($result))
		{
			return false;
		}
		$options = $result->current()->toArray();
		$model->setOptions($options);
		return true;
	}

	/**
	 * Zwraca liste modeli
	 *
	 * @param string|Zend_Db_Select $where
	 * @param string $order
	 * @param ing $limit
	 * @param int $page
	 * @return array 
	 */
	public function fetchAll($where = null, $order = null, $limit = null,
		$page = null)
	{
		$select = $this->table()->select()->where('status <> 0');
		if (!empty($where))
		{
			if (!is_array($where))
			{
				$where = array($where);
			}
			foreach ($where as $cond => $param)
			{
				if (is_int($cond))
				{
					$select->where($param);
				}
				else
				{
					$select->where($cond, $param);
				}
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
		$result = $this->table()->fetchAll($select);
		$entries = array();
		foreach ($result as $row)
		{
			$entries[] = new $this->_modelName($row->toArray());
		}

		return $entries;
	}

	public function fetchAllAssoc($where = null, $order = null, $limit = null,
		$page = null)
	{
		$entries = array();
		$data = $this->fetchAll($where, $order, $limit, $page);
		foreach ($data as $model)
		{
			$entries[$model->getId()] = $model;
		}
		return $entries;
	}

	/**
	 * Zwraca ilosc elementow
	 *
	 * @param mixed $where - string|select
	 * @return int
	 */
	public function countAll($where = null)
	{
		$select = $this->table()->select()
				->from($this->table(), array('n' => 'COUNT(*)'))
				->where('status <> 0');
		if (!empty($where))
		{
			if (!is_array($where))
			{
				$where = array($where);
			}
			foreach ($where as $cond)
			{
				$select->where($cond);
			}
		}
		$result = $this->table()->fetchRow($select);
		return (int)$result->n;
	}

	public function save(Cms_Model $model)
	{
		$data = $model->getOptions();
		if (null === ($id = $model->getId()))
		{
			// nowy
			$id = $this->table()->insert($data);
			$model->setId($id);
		}
		else
		{
			// edycja
			$this->table()->update($data, $this->__db()->quoteInto('id = ?', $id));
		}

		return $this;
	}

	/**
	 * Ustawia status elementu na 0 - czyli kosz
	 *
	 * @param Cms_Model $element
	 */
	public function delete(Cms_Model $element)
	{
		$where = $this->__db()->quoteInto('id = ?', $element->getId());
		return (bool)$this->table()->update(array('status' => 0), $where);
	}

	/**
	 * Usuwa elementcalkowicie z systemu
	 * Uwaga - proces nieodwracalny!
	 *
	 * @param Cms_Model $element
	 */
	public function purge(Cms_Model $element)
	{
		$where = $this->__db()->quoteInto('id = ?', $element->getId());
		return (bool)$this->table()->delete($where);
	}

}