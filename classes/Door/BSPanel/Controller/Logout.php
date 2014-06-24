<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Controller;
/**
 * Description of Logout
 *
 * @author serginho
 */
class Logout extends \Door\Core\Controller {
	
	protected $redirect_uri = "";
	
	public function execute() {
		
		$this->app->auth->logout();
		$this->redirect($this->redirect_uri);
		
	}
	
}
