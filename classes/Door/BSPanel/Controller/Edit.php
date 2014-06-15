<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Controller;
use Door\BSPanel\FormBuilder;
use Door\Core\Database\Relation;
use Door\Core\Database\Type;
use Door\Core\Model;
use Door\Core\Helper\Arr;

/**
 * Description of Edit
 *
 * @author serginho
 */
class Edit extends Layout{		
	
	
	protected $model = null;
	
	protected $return_uri = "";
	
	protected $filter_param = null;
	
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
		
		$edit_fields = $this->configure_fields($model);
		
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
		
		if(count($_POST) > 0)
		{						
			foreach($edit_fields as $field_data)
			{
				if($field_data['type'] == 'boolean')
				{
					$name = $field_data['name'];
					$model->$name = false;
				}
			}
			
			$model->values($_POST);
			
			if( ! $model->check())
			{
				$view->errors = true;
			}
			elseif( ! $model->loaded())
			{
				$model->save();
				$this->redirect($return_uri);								
			}
			else				
			{				
				$model->save();
				$view->success = true;
			}
		}				
		
		$builder = new FormBuilder();
		$builder->set_model($model);
		$builder->add_fields($edit_fields);
		$builder->data('view_images_uri', $this->view_images_uri);
		$builder->data('upload_image_uri', $this->upload_image_uri);
		$builder->data('upload_images_uri', $this->upload_images_uri);
		
		$view->fields = $builder->render();	
		
		$this->response->body($view->render());
		
		$this->add_script('ckeditor/ckeditor');
		
		parent::execute();
		
	}
	
	protected function get_field_view($cfg, Model $model)
	{
		$view = $this->app->views->get("bspanel/".$cfg['type']);
		
		$view->name = $cfg['name'];
		$view->value = $model->$name;
		$view->model = $model;
		
	}
	
	protected function configure_fields(Model $model)
	{
		$return_value = array();
		foreach($this->edit_fields as $edit_field_cfg)
		{
			if(is_string($edit_field_cfg))
			{
				$edit_field_cfg = $this->field_cfg_from_string($edit_field_cfg, $model);								
			}
			$return_value[] = $edit_field_cfg;
		}
		return $return_value;
	}
	
	protected function field_cfg_from_string($name, Model $model)
	{
		$edit_field_cfg = array(
			'name' => $name
		);

		$field = Arr::get($model->fields(), $name);
		$relation = Arr::get($model->get_relations(), $name);

		if($field !== null)
		{
			switch($field['type'])
			{
				case Type::BOOLEAN:
					$edit_field_cfg['type'] = 'boolean';
					break;
				case Type::INTEGER:
				case Type::STRING:
					$edit_field_cfg['type'] = 'text';
					break;																
				default:
					throw new Exception('not implemented');
			}
		}
		elseif($relation !== null && $relation['model'] == 'Image')
		{
			if($relation['type'] == Relation::MANY_TO_ONE)
			{
				$edit_field_cfg['type'] = 'image';
			}
			elseif($relation['type'] == Relation::MANY_TO_MANY)
			{
				$edit_field_cfg['type'] = 'gallery';
			}
			else
			{
				throw new Exception('bad relation '.$name);
			}
		}
		elseif($relation !== null)
		{
			switch($relation['type'])
			{
				case Relation::MANY_TO_MANY : 
					$edit_field_cfg['type'] = 'tags';
					break;
				default:
					throw new Exception('relation not implemented');					
			}
		}
		else
		{
			throw new Exception('property not found');
		}		
		
		return $edit_field_cfg;
	}
	
}
