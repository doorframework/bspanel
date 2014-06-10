<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */

namespace Door\BSPanel\Controller;
use Door\Core\Helper\Arr;

/**
 * Description of Panel
 *
 * @author serginho
 */
class ModelsList extends Layout  {
	
	protected $model = null;
	
	protected $filter_param = null;
	
	protected $buttons = array();
	
	protected $return_uri = null;
	
	protected $create = true;
	
	protected $columns = array();
	
	public function execute() {				
		
		$model = $this->app->models->factory('model');
		
		$return_uri = $this->return_uri;
		
		if($this->filter_param !== null)
		{
			
			
			$filter_value = Arr::get($_GET, $this->filter_param);
			
			if($filter_value === null)
			{
				throw new Exception("filter param {$this->filter_param} not specified");
			}
			
			$model->where($this->filter_param,'=',$filter_value);
			
			$return_uri = str_replace("<id>", $filter_value, $return_uri);
		}
		
		$view = $this->app->views->get("bspanel/list");
		$view->model = $model;
		$view->items = $model->find_all()->as_array();		
		$view->create_button = $this->create;
		$view->uri = $this->request->uri();
		$view->return_uri = $return_uri;
		$view->buttons = $this->buttons;
		$view->columns = $this->columns;
		
		$this->response->body($view->render());
		
		parent::execute();
	}
	
}
