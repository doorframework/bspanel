<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Data;
use Door\Core\Model;
/**
 * Description of IColumn
 *
 * @author serginho
 */
interface IColumn {
	
	/**
	 * @return string
	 */
	public function name();
	
	/**
	 * @param Model $model
	 * @return string
	 */
	public function render(Model $model);
	
}
