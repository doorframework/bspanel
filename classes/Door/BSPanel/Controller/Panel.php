<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */

namespace Door\BSPanel\Controller;

/**
 * Description of Panel
 *
 * @author serginho
 */
class Panel extends Layout  {
	
	public function execute() {				
		
		
		$view = $this->app->views->get("bspanel/panel");
		
		$this->response->body($view);
		
		parent::execute();
	}
	
}
