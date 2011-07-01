<?php

class Cms_Form_Element_Image extends Zend_Form_Element_Xhtml
{
	
	public function init()
	{
		parent::init();
		
		$this->addDecorator('ViewScript', array(
            'viewScript' => 'form/element/image.phtml'
        ));
	}

	public function getDefault() {
		return $this->getAttrib('default');
	}

	/**
	 * Powiekszenie i zmiana w wjezdzajacej ramce, dobre dla wiekszych obrazkow
	 * @return bool
	 */
	public function getModal() {
		return (bool)$this->getAttrib('modal');
	}
}