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
 * Description of KeyValueEditor
 *
 * @author serginho
 */
class KeyValueEditor extends Layout {
	
	protected $model = null;
	
	protected $key_field = "key";
	
	protected $value_field = "value";
	
	protected $fields = array();
	
	public function execute() {
				
		$view = $this->app->views->get('bspanel/edit');
		
		if(count($_POST) > 0)
		{
			foreach($this->fields as $field)
			{									
				$name = $field['name'];
				$type = $field['type'];
				$value = Arr::get($_POST, $name);
				
				if($type == 'boolean')
				{
					$value == (boolean)$value;
				}								
				
				$model = $this->app->models->factory($this->model, array(
					$this->key_field => $name
				));							
				
				if( ! $model->loaded())
				{
					$model = $this->app->models->factory($this->model);
					$model->{$this->key_field} = $name;
				}

				$model->{$this->value_field} = $value;
								
				$model->save();	
				
				$view->success = true;
				
			}
		}	
		
		$builder = new FormBuilder($this->app);
		
		foreach($this->fields as $field)
		{
			$name = $field['name'];
			$model = $this->app->models->factory($this->model, array(
				$this->key_field => $name
			));
			
			if($model->loaded())
			{
				$builder->set_value($name, $model->{$this->value_field});
			}
			
			$builder->add_field($field);
		}				
		
		$view->title = $this->title;
		$view->fields = $builder->render();
		
		$this->response->body($view);
		
		
		
		parent::execute();
	}
	
}
