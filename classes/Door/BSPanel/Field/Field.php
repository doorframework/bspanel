<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Field;
use Door\Core\Model;
use Door\Core\Application;
/**
 * Description of Field
 *
 * @author serginho
 */
abstract class Field implements IField {
	
	protected $name;
	
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	public function name()
	{
		return $this->name;
	}		
	
	/**
	 * Filling model with specified data for saving
	 * @param \Door\Core\Model $model
	 * @param array $data typically data that come from $_POST
	 */
	public function fill_model(Model $model, & $data)
	{
		$name = $this->name;
		if(isset($data[$name]))
		{
			$model->$name = $data[$name];
		}
	}
	
	protected function get_layout(Application $app)
	{
		$layout = $app->views->get('bspanel/field/layout');
		$layout->name = $this->name;
		return $layout;
	}
	
}
