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
	
	protected $filter_model = null;
	
	protected $buttons = array();
	
	protected $return_uri = null;
	
	protected $create = true;
	
	protected $columns = array();
	
	protected $sortable = false;
	
	protected $sort = null;
	
	protected $sort_column = 'sort';
	
	protected $grid = false;
	
	public function execute() {		
		
		if($this->grid)
		{
			if(isset($_GET['data']))
			{
				$this->grid_data();
			}
			else
			{
				$this->show_grid();
			}
			
		}
		else
		{
			$this->show_list();
		}
		
		parent::execute();
	}
	
	protected function grid_data()
	{
		$this->show_layout = false;
		
		$return_value = array(
			'recordsTotal' => 0,
			'recordsFiltered' => 0,
			'data' => array()
		);
		
		$limit = Arr::get($_GET, 'length', 10);			
		$offset = Arr::get($_GET, 'start', 0);
		
		
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
		
		$cursor = $model->find_all();
		
		$return_value['recordsTotal'] = $cursor->count();
		
		$cursor->limit($limit);
		$cursor->skip($offset);
		
		if($this->sortable)
		{
			$cursor->sort(array($this->sort_column => 1));
		}
		elseif($this->sort !== null)
		{
			$cursor->sort($this->sort);
		}
		
		$fields = $model->get_fields();

		while($item = $cursor->getNext())
		{
			$item_data = array();
			foreach($this->columns as $column)
			{
				$val = "";
				if($column instanceof \Door\BSPanel\Data\IColumn) {

					$val = $column->render($item);

				} else {
					$val = $item->$column;
					if($fields[$column]['type'] == 'boolean')
					{
						$val = Icons::show($val ? "ok" : "remove");
					}
					if($fields[$column]['type'] == 'date')
					{
						$val = date('d.m.Y', $val);
					}										
				}										
				
				$item_data[] = $val;
			}
			
			$buttons_text = "";
			foreach($this->buttons as $button)
			{
				$buttons_text .= " " . $button->render($this->app, array("<id>" => $item->pk()));
			}			
			
			$item_data[] = $buttons_text;
			$return_value['data'][] = $item_data;
		}
		
		$return_value['recordsFiltered'] = count($return_value['data']);
		
		$this->response->headers("Content-Type","application/json; charset=utf-8");
		$this->response->body(json_encode($return_value));
	}
	
	protected function show_grid()
	{
		$this->add_script('datatables/media/js/jquery.dataTables');
		$this->add_style('datatables/media/css/jquery.dataTables');
		
		$model = $this->app->models->factory($this->model);
		
		$view = $this->app->views->get('bspanel/grid');
		$view->model = $this->model;
		$view->filter_param = $this->filter_param;
		$view->filter_value = Arr::get($_GET, $this->filter_param);
		
		$return_uri = $this->return_uri;
		
		$view->create_button = $this->create;
		$view->uri = $this->request->uri();
		$view->return_uri = $return_uri;
		$view->buttons = $this->buttons;
		$view->columns = $this->columns;
		$view->sortable = $this->sortable;			
		
		$l = $this->app->lang;
		
		if($this->filter_model === null)
		{
			$view->title = $l->get_ucf($model->get_model_name().".list");
		}
		else
		{
			$filter_model = $this->app->models->factory($this->filter_model, $filter_value);
			$view->title = $filter_model->name().": ".$l->get_ucf($model->get_model_name().".list");
		}		
		
		$this->title = $view->title;		
		
		$this->response->body($view->render());
	}
	
	protected function show_list()
	{
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
		elseif($this->sort !== null)
		{
			$cursor->sort($this->sort);
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
		
		$l = $this->app->lang;
		
		if($this->filter_model === null)
		{
			$view->title = $l->get_ucf($model->get_model_name().".list");
		}
		else
		{
			$filter_model = $this->app->models->factory($this->filter_model, $filter_value);
			$view->title = $filter_model->name().": ".$l->get_ucf($model->get_model_name().".list");
		}		
		
		$this->title = $view->title;
		
		$this->response->body($view->render());		
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
