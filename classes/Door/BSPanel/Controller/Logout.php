<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace App\Controller\Admin;
/**
 * Description of Logout
 *
 * @author serginho
 */
class Logout extends \Door\Core\Controller {
	
	public function execute() {
		
		$this->app->auth->logout();
		$this->redirect("admin/login");
		
	}
	
}
