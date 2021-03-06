<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel;
use Door\BSPanel\Field\IField;
use Door\Core\Helper\Arr;
use Door\Core\Model;
use Exception;
use Door\Core\Database\Type;	
use Door\Core\Database\Relation;
use Door\Core\Application;

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
		'wysiwyg',
		'date',
		'textarea'
	);
	
	protected $data = array();
	
	protected $fields = array();
	
	/**
	 *
	 * @var Model
	 */
	protected $model = null;
	
	protected $values = array();
	
	/**
	 *
	 * @var Application
	 */
	protected $app;
	
	
	public function __construct(Application $app) {
		
		$this->app = $app;
		
	}
	
	
	public function add_field($config)
	{
		$this->fields[] = $config;
	}
	
	public function add_fields(array $configs)
	{
		array_map(array($this,'add_field'), $configs);
	}
	
	protected function render_field($config)
	{
		if($config instanceof IField)
		{
			return $config->render($this->model);
		}
		
		if(is_string($config))
		{
			$config = $this->field_cfg_from_string($config);
		}
		
		$type = Arr::get($config, 'type');
		$name = Arr::get($config, 'name');
				
		if($type == 'tabs')
		{
			return $this->render_tabs($config);
		}		
		
		if( ! in_array($type, $this->types))
		{
			throw new Exception("bad type supported");
		}	
		
		$view = $this->app->views->get("bspanel/field/".$type, $this->data);
		
		$view->name = $name;
		$view->value = $this->get_value($name);
		$view->model = $this->model;
		$view->field_config = $config;
		
		$layout = $this->app->views->get('bspanel/field/layout');

		$layout->name = $name;
		$layout->field = $view->render();

		return $layout->render();		
	}
	
	protected function render_tabs($config)
	{
		$tab_buttons = array();
		$tab_contents = array();
		
		$is_first = true;
		
		foreach(Arr::get($config, 'tabs') as $tab_config)
		{
			$class = $is_first ? "active" : "";
			
			$tab_id = uniqid();
			$name = $this->app->lang->get_ucf($tab_config['name']);
			$fields = $tab_config['fields'];
			$tab_buttons[] = "<li class='$class'><a href='#{$tab_id}' data-toggle='tab'>{$name}</a></li>";
			
			$fields_rendered = array();
			foreach($fields as $field_config)
			{
				$fields_rendered[] = $this->render_field($field_config);
			}
						
			$tab_contents[] = "<div class='tab-pane $class' id='{$tab_id}'>".
					implode("\n", $fields_rendered).
					"</div>";
			
			
			$is_first = false;
		}
		
		return "<div class='form-group'><div class='col-sm-12'>"
		. "<ul class='bspanel-tabs nav nav-tabs' data-tabs='tabs'>"
		. implode("\n", $tab_buttons)
		. "</ul>"
		. "<br/><div class='tab-content'>"
		. implode("\n", $tab_contents)
		. "</div></div>";
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
	
	public function set_value($name, $value)
	{
		$this->values[$name] = $value;
	}
	
	public function set_values(array $values)
	{
		foreach($values as $key => $value)
		{
			$this->set_value($key, $value);
		}
	}
	
	public function get_value($name)
	{
		if($this->model !== null)
		{
			return $this->model->$name;
		}
		else
		{
			return Arr::get($this->values, $name);
		}
	}
	
	public function data($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	protected function configure_field($field_cfg)
	{
		if(is_string($field_cfg))
		{
			$field_cfg = $this->field_cfg_from_string($field_cfg);								
		}
		return $field_cfg;		
	}
	
	protected function field_cfg_from_string($name)
	{
		$edit_field_cfg = array(
			'name' => $name
		);
		
		if($this->model == null)
		{
			throw new Exception('model not set');
		}

		$field = Arr::get($this->model->fields(), $name);
		$relation = Arr::get($this->model->get_relations(), $name);

		if($field !== null)
		{
			switch($field['type'])
			{
				case Type::BOOLEAN:
					$edit_field_cfg['type'] = 'boolean';
					break;
				case Type::DOUBLE:
				case Type::INTEGER:
				case Type::STRING:
					$edit_field_cfg['type'] = 'text';
					break;	
				case Type::DATE:
					$edit_field_cfg['type'] = 'date';
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
	
	public function get_fields_types()
	{
		return $this->_get_fields_types($this->fields);
	}
	
	protected function _get_fields_types(array $fields)
	{
		$return_value = array();
		
		foreach($fields as $field_cfg)
		{
			$field = $this->configure_field($field_cfg);			
			if($field instanceof IField)
			{
				$return_value[$field->name()] = $field;
			}
			elseif($field['type'] !== 'tabs')
			{				
				$return_value[$field['name']] = $field['type'];				
			}
			else
			{
				foreach($field['tabs'] as $tab_config)
				{
					$return_value += $this->_get_fields_types($tab_config['fields']);
				}
			}
		}		
		
		return $return_value;
	}
	
}
