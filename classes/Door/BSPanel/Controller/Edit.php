<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Controller;
use Door\BSPanel\Field\IField;
use Door\BSPanel\FormBuilder;
use Door\Core\Database\Relation;
use Door\Core\Database\Type;
use Door\Core\Model;
use Door\Core\Helper\Arr;
use Exception;

/**
 * Description of Edit
 *
 * @author serginho
 */
class Edit extends Layout{		
	
	
	protected $model = null;
	
	protected $return_uri = "";
	
	protected $filter_param = null;
	
	protected $filter_model = null;
	
	protected $edit_fields = array();
	
	protected $view_images_uri = "";
	
	protected $upload_images_uri = "";
	
	protected $upload_image_uri = "";
	
	
	public function execute() {				
		
		$return_uri = $this->return_uri;				
		
		$id = $this->param('id');
		
		if( ! $this->app->is_id($id))
		{
			$id = null;
		}
		
		$model = $this->app->models->factory($this->model, $id);		
		
		if($id !== null && false == $model->loaded())
		{
			$this->redirect($this->return_uri);
			return;
		}			
		
		$filter_value = null;
		
		if($this->filter_param !== null)
		{
			$filter_param = $this->filter_param;
			if($id === null)
			{
				$filter_value = Arr::get($_GET, $this->filter_param);
				if($filter_value === null)
				{
					throw new Exception("filter value not set");
				}
				$model->$filter_param = $filter_value;
				
			}
			else
			{				
				$filter_value = $model->$filter_param;
			}
			
			$return_uri = str_replace("<id>", $filter_value, $return_uri);
			
		}
		
		$view = $this->app->views->get("bspanel/edit");
		$view->model = $model;
		$view->return_uri = $return_uri;
		$view->filter_param = $this->filter_param;	
		$view->filter_value = $filter_value;
		
		$l = $this->app->lang;
		
		if($this->filter_model === null)
		{
			$view->title = $l->get_ucf($model->get_model_name()).": ".$l->get("editing");
		}
		else
		{
			$filter_model = $this->app->models->factory($this->filter_model, $filter_value);
			$view->title = $filter_model->name()." : ".$model->name()." : ".$l->get("editing");
		}
		
		$this->title = $view->title;		
		
		$builder = new FormBuilder($this->app);
		$builder->set_model($model);
		$builder->add_fields($this->edit_fields);
		$builder->data('view_images_uri', $this->view_images_uri);
		$builder->data('upload_image_uri', $this->upload_image_uri);
		$builder->data('upload_images_uri', $this->upload_images_uri);		
		
		if(count($_POST) > 0)
		{						
			$field_types = $builder->get_fields_types();
			foreach($field_types as $name => $type)
			{
				if($type instanceof IField)
				{
					$type->fill_model($model, $_POST);
				}				
				elseif( !array_key_exists($name, $_POST))						
				{					
					if($type == 'boolean')
					{
						$model->$name = false;
					}
					elseif($type == 'tags')
					{
						$model->$name = array();
					}
				}
			}
			
			$fields_keys = array_keys($model->fields());
			$relations_keys = array_keys($model->relations());						
		
			$model->values($_POST, $fields_keys);
			
			if( ! $model->check())
			{
				$view->errors = true;
			}
			elseif( ! $model->loaded())
			{
				$model->save();
				$model->values($_POST, $relations_keys);
				$model->save();				
				$this->redirect($return_uri);								
			}
			else				
			{				
				$model->save();
				$model->values($_POST, $relations_keys);
				$model->save();
				$view->success = true;
			}
		}						
		
		$view->fields = $builder->render();	
		
		$this->response->body($view->render());
		
		$this->add_script('ckeditor/ckeditor');
		
		parent::execute();
		
	}
	
}
