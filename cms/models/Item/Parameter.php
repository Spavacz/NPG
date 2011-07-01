<?php

/**
 * 
 * Klasa wspomagajaca Item
 * @author spav
 * 
 */
class Cms_Model_Item_Parameter extends Cms_Model_Item_Parameter_Abstract
{

	public static function factory($id, $options = array())
	{
		if ($id == 1 and $options['value'] == 2) $parameter = null;
		$dbTable = new Cms_Db_Mapper_Item_Parameter();
		$dbTable->find((int)$id, $parameter);
		$parameter->setOptions($options);
		return $parameter;
	}

}