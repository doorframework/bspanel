<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Configurator;
use Door\Core\Application;
use Door\Core\Model;
/**
 * Description of SimpleList
 *
 * @author serginho
 */
class DataList extends Configurator{
	
	public $buttons = array();
	
	public $create_btn = false;
	
	public $delete_btn = false;
	
	public function __construct(Model $model) {
		
		
		
	}
	
	public function configure(Application $app) {
		;
	}
	
}
