<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel;
use Door\Core\Helper\Arr;
use Door\Core\Model;
/**
 * Description of FormBuilder
 *
 * @author serginho
 */
class FormBuilder {
	
	protected $types = array(
		'text',
		'boolean',
		'tags',
		'image',
		'gallery',
		'wysiwyg'
	);
	
	protected $data = array();
	
	protected $fields = array();
	
	/**
	 *
	 * @var Model
	 */
	protected $model = null;
	
	public function add_field(array $config)
	{
		$this->fields[] = $config;
	}
	
	public function add_fields(array $configs)
	{
		array_map(array($this,'add_field'), $configs);
	}
	
	protected function render_field($config)
	{
		$type = Arr::get($config, 'type');
		$name = Arr::get($config, 'name');
		
		if( ! in_array($type, $this->types))
		{
			throw new Exeption("bad type supported");
		}	
		
		$view = $this->model->app()->views->get("bspanel/field/".$type, $this->data);
		
		$view->name = $name;
		$view->value = $this->model->$name;
		$view->model = $this->model;
		
		$layout = $this->model->app()->views->get('bspanel/field/layout');
		
		$layout->name = $name;
		$layout->field = $view->render();
		
		return $layout->render();
		
	}
	
	public function render()
	{
		$return_value = array();
		foreach($this->fields as $config)
		{
			$return_value[] = $this->render_field($config);
		}
		
		return implode("\n", $return_value);
	}
	
	public function __toString() {		
		try
		{
			return $this->render();
		} 
		catch (\Exception $ex) 
		{			
			return $ex->getMessage();			
		}
	}
	
	public function set_model(Model $model)
	{
		$this->model = $model;
		
		if($model instanceof FormModelInterface)
		{
			$this->add_fields($model->get_panel_form());
		}
	}
	
	public function data($key, $value)
	{
		$this->data[$key] = $value;
	}
	
}
