<?php

class Cms_Form_Element_Images extends Zend_Form_Element_Xhtml
{
	
	public function init()
	{
		parent::init();
		
		$this->addDecorator('ViewScript', array(
            'viewScript' => 'form/element/images.phtml'
        ));
	}
}