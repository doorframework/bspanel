<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Field;
use Door\Core\Model;
use Door\Core\Helper\Form;
/**
 * Description of Enum
 *
 * @author serginho
 */
class Enum extends Field{
	
	protected $options = array();
	
	public function __construct($name, array $options) {
		
		$this->options = $options;
		
		parent::__construct($name);
	}
	
	public function render(Model $model) {				
		
		$content = Form::select($this->name, $this->options, $model->get($this->name), array());
		$layout = $this->get_layout($model->app());
		$layout->name = $this->name;
		$layout->field = $content;		
		return $layout->render();
		
	}
	
}
