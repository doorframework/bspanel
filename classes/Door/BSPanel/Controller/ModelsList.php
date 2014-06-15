<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */

namespace Door\BSPanel\Controller;
use Door\Core\Helper\Arr;
use Door\Core\Model;
use MongoId;
use Exception;

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
	
	protected $sortable = false;
	
	protected $sort_column = 'sort';
	
	public function execute() {				
		
		$model = $this->app->models->factory($this->model);
		
		$filter_value = null;
		
		$return_uri = $this->return_uri;
		
		if($this->filter_param !== null)
		{
			
			
			$filter_value = Arr::get($_GET, $this->filter_param);
			
			if($filter_value === null)
			{
				throw new Exception("filter param {$this->filter_param} not specified");
			}
			
			$model->where($this->filter_param,'=',new MongoId($filter_value));
			
			$return_uri = str_replace("<id>", $filter_value, $return_uri);			
		}
		
		
		
		if(isset($_POST['update_sort']))
		{
			$this->update_sort($model->find_all()->as_array(), explode(",", $_POST['ids']));
			$this->response->body("true");
			$this->show_layout = false;
			return;
		}
		
		$view = $this->app->views->get("bspanel/list");
		$view->model = $model;
		$cursor = $model->find_all();
		if($this->sortable)
		{
			$cursor->sort(array($this->sort_column => 1));
		}
		
		$view->items = $cursor->as_array();		
		$view->create_button = $this->create;
		$view->uri = $this->request->uri();
		$view->return_uri = $return_uri;
		$view->buttons = $this->buttons;
		$view->columns = $this->columns;
		$view->sortable = $this->sortable;		
		$view->filter_param = $this->filter_param;
		$view->filter_value = $filter_value;
		$this->response->body($view->render());
		
		parent::execute();
	}
	
	protected function update_sort(array $items, array $sorted_ids)
	{
		foreach($items as $item)
		{
			$id = $item->pk();
			$index = array_search($id, $sorted_ids);
			if($index !== false)
			{
				$item->sort = $index;
				$item->save();
			}
		}
	}
	
}
