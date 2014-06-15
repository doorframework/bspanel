<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Controller;
/**
 * Description of Layout
 *
 * @author serginho
 */
class Layout extends \Door\Core\Controller\Layout {
	
	protected $layout_name = "bspanel/layout";
	
	public function init() {
		
		$this->add_script("components/jquery/jquery.min");
		$this->add_script("components/jquery-ui/jquery-ui-built");
		$this->add_script("components/form/jquery.form");
		$this->add_script("components/select2/select2");
		$this->add_script("components/bootstrap/js/bootstrap");
		$this->add_script("bspanel/panel/script");
		$this->add_script("bspanel/panel/image_uploads");

		$this->add_style("components/bootstrap/css/bootstrap");
		//$this->add_style("components/bootstrap/css/bootstrap-theme");		
		$this->add_style("components/jquery-ui/themes/base/jquery-ui");
		$this->add_style("components/select2/select2");
		$this->add_style("bspanel/devoops/css/style");
		$this->add_style("bspanel/panel/styles");
		$this->add_style("font-awesome/css/font-awesome");
		//$this->add_script("devoops/js/devoops");
		
		$this->title = "Админка ".$this->app->url->hostname();
		
		parent::init();
	}

	
}
