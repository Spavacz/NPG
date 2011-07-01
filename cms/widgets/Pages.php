<?php
class Cms_Widget_Pages extends Cms_Widget
{
	public function subpagesAction()
	{
		$pages = new Cms_Db_Mapper_Page();
		$this->view()->navigation( $pages->fetchNavigation() );
	}
}