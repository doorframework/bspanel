<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Configurator;
use Door\Core\Application;
use Door\Bootstrap\Helper\Icons;
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
	
	public $btn_size = 'xs';
	
	public $btn_type = 'info';
	
	public function __construct($name, $uri, $icon, $attributes = array())
	{
		$this->name = $name;
		$this->uri = $uri;
		$this->icon = $icon;
		$this->attributes = $attributes;
		
		foreach(array('btn_type','btn_size') as $key)
		{
			if(isset($attributes[$key]))
			{
				$this->$key = $attributes[$key];
				unset($attributes[$key]);
			}
		}
	}
	
	public function render(Application $app, $replaces = array())
	{		
		$uri = $this->uri;
		if(count($replaces) > 0)
		{
			$uri = str_replace(array_keys($replaces), array_values($replaces), $this->uri);
		}
		
		$attributes = $this->attributes;

		if( ! isset($attributes['class']))
		{
			$attributes['class'] = "";
		}

		$attributes['class'] .= " btn btn-{$this->btn_size} btn-{$this->btn_type} btn-label-left";

		return $app->html->anchor($uri, 
				Icons::show($this->icon)." ".$app->lang->get_ucf($this->name), 
				$attributes)." ";		
	}
	
	public function __toString() {
		return $this->render();
	}
	
}
