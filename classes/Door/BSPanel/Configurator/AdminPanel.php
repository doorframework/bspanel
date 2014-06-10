<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Configurator;
use Door\Core\Application;
use Door\Core\Helper\Arr;

/**
 * Description of AdminPanel
 *
 * @author serginho
 */
class AdminPanel {
	
	protected $prefix = "admin";
	
	protected $menu_items = array();
	
	protected $editable_lists = array();
	
	protected $lists = array();
	
	/**
	 *
	 * @var Application
	 */
	protected $app;
	
	public function prefix($prefix = null)
	{
		if($prefix === null)
		{
			return $this->prefix;
		}
		
		$this->prefix = trim($prefix,"/");
	}
	
	public function add_menu_items(array $items)
	{
		foreach($items as $item)
		{
			$this->add_menu_item($item['name'], $item['uri'], $item['icon']);
		}
	}
	
	public function add_menu_item($name, $uri, $icon = null)
	{		
		$this->menu_items[] = new Action($name, trim($uri,"/"), $icon);
	}
	
	public function add_editable_list($uri, $model, $params)
	{
		$this->editable_lists[] = array(
			'uri' => trim($uri,"/"),
			'model' => $model,
			'params' => $params
		);
		
		return $this;
	}	
	
	protected function uri($uri)
	{
		if($uri === null)
		{
			return null;
		}		
		if(strlen($this->prefix) > 0)
		{
			$uri = trim($this->prefix."/".trim($uri,"/"),"/");
		}
		return $uri;
	}
	
	public function configure(Application $app)
	{
		$this->app = $app;
		$this->configure_menu();
		$this->configure_pages();
	}
	
	protected function configure_menu()
	{
		$menu_items = $this->menu_items;
		
		for($i = 0 ; $i < count($menu_items); $i++)
		{
			$menu_items['uri'] = $this->uri($menu_items['uri']);
		}
		
		$this->app->router->wrap($this->prefix, "bspanel/left_menu", array(
			'menu_items' => $menu_items
		));		
		
	}
	
	protected function configure_pages()
	{
		$editable_lists = $this->editable_lists;
		
		for($i = 0 ; $i < count($editable_lists); $i++)
		{
			$editable_lists['uri'] = $this->uri($editable_lists['uri']);
		}			
		
		foreach($editable_lists as $cfg)
		{
			$this->configure_editable_list($cfg['uri'], 
					$cfg['model'], $cfg['params']);
		}
	}
	
	protected function configure_editable_list(Application $app, $uri, $model, $params)
	{
		$params += array(
			'delete' => true,
			'create' => true,
			'list_columns' => array(
				'name','visible'
			),
			'edit_fields' => array(
				'name'
			)
		);		
		
		$list_buttons = $this->get_list_buttons($params);
		
		if($params['removable'])
		{
			$this->app->router->add($uri."/delete", $uri."/delete/<id>", "core/delete")->add_config(array(
				'return_uri' => $uri,
				'model' => $model
			));		
			
			$list_buttons[] = new Action("Delete", $uri."/delete/<id>", 'glyphicon-remove');
		}		
		
		$list_buttons[] = new Action("Edit", $uri."/edit/<id>", 'glyphicon-edit');
		
		$this->app->router->add($uri, $uri, "bspabel/list")->add_config(array(
			'create' => $params['create'],
			'buttons' => $list_buttons,
			'model' => $model,
			'columns' => $params['list_columns'],
			'uri' => $uri,
			'filter_param' => Arr::get($params, 'filter_param'),
			'return_uri' => $this->uri(Arr::get($params, 'return_uri'))
		));
		
		$this->app->router->add($uri."/edit", $uri."/edit(/<id>)", "bspabel/edit")->add_config(array(
			'return_uri' => $uri,
			'model' => $model,
			'filter_param' => Arr::get($params, 'filter_param'),
			'edit_fields' => $params['edit_fields']
		));				
		
		
	}
	
	protected function get_list_buttons(array $params)
	{
		$list_buttons = Arr::get($params, 'list_buttons', array());	
		
		for($i = 0; $i < count($list_buttons); $i++)
		{
			if(is_array($list_buttons[$i]))
			{
				$list_buttons[$i] = new Action(
						$list_buttons[$i]['name'],
						$this->uri($list_buttons[$i]['uri']), 
						$list_buttons[$i]['icon']);
			}
		}		
		
		return $list_buttons;
	}
	
	
}

