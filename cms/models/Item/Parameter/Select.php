<?php

/**
 * 
 * Klasa reprezentujaca parametr typu checkbox
 * @author spav
 * 
 */
class Cms_Model_Item_Parameter_Select extends Cms_Model_Item_Parameter_Abstract
{

	public function setValue($value)
	{
		// @todo sprawdzenie czy opcja istnieje 
		$this->__value = !empty($value) ? $value : null;
		return $this;
	}

	public function getValue($string = false)
	{
		if ($string)
		{
			$options = $this->getOptionsValues();
			foreach ($options as $option)
			{
				if ($option['id'] == $this->__value) $value = $option['value'];
			}
			return $this->getName() . ': ' . $value;
		}
		return parent::getValue();
	}

}