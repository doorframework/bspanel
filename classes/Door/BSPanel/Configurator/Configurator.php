<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Configurator;
use Door\Core\Application;
/**
 * Description of IConfigurator
 *
 * @author serginho
 */
abstract class Configurator {
	
	protected $prefix = "/";
	
	public function set_prefix($prefix)
	{
		$this->prefix = $prefix;
	}
	
	public function prefix()
	{
		return $this->prefix;
	}
	
	abstract public function configure(Application $app);
	
}
