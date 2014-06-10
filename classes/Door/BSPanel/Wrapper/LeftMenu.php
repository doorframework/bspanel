<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Wrapper;
use Door\Core\Wrapper;
use Door\BSPanel\Controller\Layout;
/**
 * Description of LeftMenu
 *
 * @author serginho
 */
class LeftMenu extends Wrapper{
	
	protected $menu_items = array();
	
	public function after() {
		
		if($this->controller instanceof Layout)
		{
			$layout = $this->controller->get_layout();
			$layout->admin_menu = $this->menu_items;			
		}
		
		parent::after();
	}
	
}
