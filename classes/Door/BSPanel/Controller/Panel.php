<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */

namespace App\Controller\Admin;

/**
 * Description of Panel
 *
 * @author serginho
 */
class Panel extends Layout  {
	
	public function execute() {				
		
		
		$view = $this->app->views->get("bootstrap/index");
		
		$this->response->body($view);
		
		parent::execute();
	}
	
}
