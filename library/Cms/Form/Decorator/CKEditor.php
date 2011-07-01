<?php

class Cms_Form_Decorator_CKEditor extends Zend_Form_Decorator_Abstract
{
	
	public function render( $content )
	{
		$script = '<script type="text/javascript">
			var editor = CKEDITOR.replace( \''. $this->getElement()->getName().'\' );
			CKFinder.setupCKEditor( editor, \'/js/ckfinder/\' );
		</script>';
		return $content . $script; 
	}
	
}