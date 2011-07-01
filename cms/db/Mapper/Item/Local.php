<?php

class Cms_Db_Mapper_Item_Local extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Locals';
	protected $_modelName = 'Cms_Model_Item_Local';

	public function save(Cms_Model_Item_Local $local)
	{
		if (is_null($local->getId()))
		{
			$local->setCreated(date('Y-m-d H:i:s'));
		}

		return parent::save($local);
	}

}