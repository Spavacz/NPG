<?php

class Cms_Db_Mapper_Item_Image extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Images';
	protected $_modelName = 'Cms_Model_Item_Image';

	public function save(Cms_Model_Item_Image $item)
	{
		if (is_null($article->getId()))
		{
			$item->setCreated(date('Y-m-d H:i:s'));
			$item->setIdAuthor(Zend_Registry::get('user')->getId());
		}

		return parent::save($item);
	}

}