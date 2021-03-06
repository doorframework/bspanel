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
	
	public $login_role = 'admin_panel';
	
	protected $menu_items = array();
	
	protected $editable_lists = array();
	
	protected $lists = array();
	
	protected $edit_pages = array();
	
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
	
	/**
	 * 
	 * @param string $uri
	 * @param string $model
	 * @param array $params Array of parameters
	 * available parameters:
	 * delete(boolean)
	 * 
	 * @return \Door\BSPanel\Configurator\AdminPanel
	 */
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
		
		$this->app->router->add($this->prefix, $this->prefix, "bspanel/panel");				
		
		$this->app->router->add($this->uri('upload_image'), $this->uri('upload_image'), "core/upload_image");				
		$this->app->router->add($this->uri('upload_images'), $this->uri('upload_images'), "core/upload_images");				
		$this->app->router->add($this->uri('view_images'), $this->uri('view_images'), "core/view_images")->add_config(array(
			'list_image' => 'bspanel_list',
			'item_image' => 'bspanel_image',
			'list_view' => 'bspanel/images_list'
		));				
		
		$this->configure_menu();		
		$this->configure_pages();
		$this->configure_kveditors();
		$this->configure_login();
		
	}
	
	protected function configure_menu()
	{
		$menu_items = $this->menu_items;
		
		for($i = 0 ; $i < count($menu_items); $i++)
		{
			$menu_items[$i]->uri = $this->uri($menu_items[$i]->uri);
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
			$editable_lists[$i]['uri'] = $this->uri($editable_lists[$i]['uri']);
		}			
		
		foreach($editable_lists as $cfg)
		{
			$this->configure_editable_list($cfg['uri'], 
					$cfg['model'], $cfg['params']);
		}
	}
	
	protected function configure_editable_list($uri, $model, $params)
	{
		$params += array(
			'return_uri' => null,
			'delete' => true,
			'create' => true,
			'list_columns' => array(
				'name','visible'
			),
			'edit_fields' => array(
				'name'
			),
			'sortable' => false,
			'sort' => null,
			'sort_column' => 'sort',
			'grid' => false
		);		
		
		$list_buttons = $this->get_list_buttons($params);
		
		$list_buttons[] = new Action("Edit", $uri."/edit/<id>", 'glyphicon-edit',array(
			'class' => 'btn-info'
		));		
		
		if($params['delete'])
		{
			$this->app->router->add($uri."/delete", $uri."/delete/<id>", "core/delete")->add_config(array(
				'model' => $model
			));		
			
			$list_buttons[] = new Action("Delete", $uri."/delete/<id>", 'glyphicon-remove', array(
				'onclick' => "return confirm('{$this->app->lang->get('confirm_delete')}')",
				'class' => 'btn-danger'
			));
		}				
		
		$filter_param = Arr::get($params, 'filter_param');
		
		$this->app->router->add($uri, $uri, "bspanel/list")->add_config(array(
			'create' => $params['create'],
			'buttons' => $list_buttons,
			'model' => $model,
			'columns' => $params['list_columns'],
			'sortable' => $params['sortable'],
			'sort' => $params['sort'],
			'uri' => $uri,
			'filter_param' => $filter_param,
			'filter_model' => Arr::get($params, 'filter_model'),
			'return_uri' => $this->uri(Arr::get($params, 'return_uri')),
			'grid' => $params['grid']
		));
		
		$uri_param = $filter_param == null ? "" : "?{$filter_param}=<id>";
		
		$this->app->router->add($uri."/edit", $uri."/edit(/<id>)", "bspanel/edit")->add_config(array(
			'return_uri' => $uri.$uri_param,
			'model' => $model,
			'filter_param' => $filter_param,
			'filter_model' => Arr::get($params, 'filter_model'),
			'edit_fields' => $params['edit_fields'],
			'view_images_uri' => $this->uri('view_images'),
			'upload_images_uri' => $this->uri('upload_images'),
			'upload_image_uri' => $this->uri('upload_image'),
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
						$list_buttons[$i]['icon'],
						Arr::get($list_buttons[$i],'attributes',array()));
			}
		}		
		
		return $list_buttons;
	}
	
	protected function configure_login()
	{
		$router = $this->app->router;		
		$router->wrap($this->prefix, "core/needauth", array(
			"login_role" => $this->login_role,
			"redirect_uri" => $this->prefix."/login"	
		));		
		$router->add($this->prefix."/logout",$this->prefix."/logout", "bspanel/logout")->add_config(array(
			'redirect_uri' => $this->prefix."/login"
		));
		$router->add($this->prefix."/login",$this->prefix."/login", "bspanel/login");
	}
	
	protected $kveditors = array();
	
	/**
	 * 
	 * @param type $uri
	 * @param type $model
	 * @param array $fields
	 * @param type $key_field
	 * @param type $value_field
	 * @return \Door\BSPanel\Configurator\AdminPanel
	 */
	public function add_kveditor($uri, $model, array $fields, $title, $key_field = "key", $value_field = "value")
	{
		$this->kveditors[] = array(
			'uri' => $uri,
			'model' => $model,
			'title' => $title,
			'fields' => $fields,
			'key_field' => $key_field,
			'values_field' => $value_field
		);
		
		return $this;
	}
	
	protected function configure_kveditors()
	{
		foreach($this->kveditors as $kveditor_config)
		{
			$uri = $this->uri($kveditor_config['uri']);
			
			$this->app->router->add($uri, $uri, "bspanel/kveditor")->add_config($kveditor_config);							
		}
	}
	
	
}

