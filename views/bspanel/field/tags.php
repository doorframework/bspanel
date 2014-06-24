<?
$variants = array();
foreach($app->models->factory($value->related_model())->find_all()->as_array() as $model)
{
	$variants[(string)$model->pk()] = $model->name();
}

echo \Door\Core\Helper\Form::select("{$name}[]", $variants, $value->get_ids(), array(
			"class" => "populate placeholder select2"
		));