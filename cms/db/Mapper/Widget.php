<?php

class Cms_Db_Mapper_Widget extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Widgets';
	protected $_modelName = 'Cms_Model_Widget';

	public function findOnPage($idInstance)
	{
		$row = $this->table()->findOnPage($idInstance);
		$entry = new Cms_Model_Widget($row->toArray());
		
		return $entry;
	}

	public function fetchAllInBlock($block)
	{
		$resultSet = $this->table()->fetchAllInBlock($block);
		$entries = array();
		foreach ($resultSet as $row)
		{
			$entries[] = new Cms_Model_Widget($row->toArray());
		}

		return $entries;
	}

	public function updateInBlock($block)
	{
		return $this->table()->updateInBlock($block);
	}

	public function deleteFromBlock(Cms_Model_Widget $widget)
	{
		$this->table()->deleteFromBlock($widget);
	}

}
