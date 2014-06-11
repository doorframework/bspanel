<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Configurator;
/**
 * Description of Action
 *
 * @author serginho
 */
class Action {
	
	public $name;
	
	public $uri;
	
	public $icon;
	
	public $attributes = array();
	
	public function __construct($name, $uri, $icon, $attributes = array())
	{
		$this->name = $name;
		$this->uri = $uri;
		$this->icon = $icon;
		$this->attributes = $attributes;
	}
	
}
