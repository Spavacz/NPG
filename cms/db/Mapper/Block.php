<?php

class Cms_Db_Mapper_Block extends Cms_Db_Mapper_Abstract
{
	protected $_dbTableName = 'Cms_Db_Table_Blocks';
	protected $_modelName = 'Cms_Model_Block';

	public function fetchAllOnPage($page)
	{
		$resultSet = $this->table()->fetchAllOnPage($page);
		$entries = array();
		foreach ($resultSet as $row)
		{
			$entries[] = new Cms_Model_Block($row->toArray());
		}

		return $entries;
	}

}
