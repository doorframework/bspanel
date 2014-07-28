<?php

/*
 * Created by Sachik Sergey
 * box@serginho.ru
 */
namespace Door\BSPanel\Field;
use Door\Core\Model;
/**
 * Description of Field
 *
 * @author serginho
 */
interface IField {

	public function name();
	
	/**
	 * Filling model with specified data for saving
	 * @param \Door\Core\Model $model
	 * @param array $data typically data that come from $_POST
	 */
	public function fill_model(Model $model, & $data);
	
	/**
	 * Render field
	 * @param \Door\Core\Model $model model that we render
	 * @return string rendered field with filled value
	 */
	public function render(Model $model);	
	
}
