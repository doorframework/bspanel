<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Data;
use Door\Core\Model;
/**
 * Description of Column
 *
 * @author serginho
 */
class Column implements IColumn{
	
	protected $name = "";
	
	public function __construct($name) {
		
		$this->name = $name;
		
	}
	
	public function name()
	{
		return $this->name;		
	}
	
	public function render(Model $model)
	{
		return (string)$model->{$this->name};
	}
		
	
}
