<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Controller;
use Door\Core\Helper\Arr;
/**
 * Description of Login
 *
 * @author serginho
 */
class Login extends Layout {
	
	protected $layout_name = "admin/login_layout";
	
	public function execute() {
		
		if(count($_POST) > 0)
		{
			$this->app->auth->login(Arr::get($_POST,'username'), Arr::get($_POST,'password'));
		}

		
		if($this->app->auth->logged_in('admin_panel'))
		{			
			$this->redirect("admin");
			return;
		}
		
		$this->add_style("bspanel/panel/login");
		$view = $this->app->views->get('bspanel/login');
		$view->is_error = count($_POST) > 0;
		$view->username = Arr::get($_POST, 'username');
		
		$this->response->body($view);
		
		parent::execute();
	}
	
}
