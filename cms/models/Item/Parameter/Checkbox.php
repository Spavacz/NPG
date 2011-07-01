<?php

/**
 * 
 * Klasa reprezentujaca parametr typu checkbox
 * @author spav
 * 
 */
class Cms_Model_Item_Parameter_Checkbox extends Cms_Model_Item_Parameter_Abstract
{

	public function setValue($value)
	{
		$this->__value = !empty($value) ? 1 : null;
		return $this;
	}

	public function getValue($string = false)
	{
		if ($string)
		{
			return $this->__value == 1 ? $this->getName() : false;
		}
		return parent::getValue();
	}

}