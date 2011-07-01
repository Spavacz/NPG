<?php

class Cms_Db_Mapper_Item_Article extends Cms_Db_Mapper_Abstract
{

	protected $_dbTableName = 'Cms_Db_Table_Articles';
	protected $_modelName = 'Cms_Model_Item_Article';

	public function save(Cms_Model_Item_Article $article)
	{
		if (is_null($article->getId()))
		{
			$article->setCreated(date('Y-m-d H:i:s'));
			$article->setIdAuthor(Zend_Registry::get('user')->getId());
		}

		return parent::save($article);
	}

}