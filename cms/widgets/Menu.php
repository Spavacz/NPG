<?php

/**
 * Menu Widgets
 *
 * @author Spav
 */
class Cms_Widget_Menu extends Cms_Widget
{

	public function mainMenuAction()
	{
		$pages = new Cms_Db_Mapper_Page();
        $nav = $pages->fetchNavigation();
        $this->view()->navigation( $nav );
		
	}

}