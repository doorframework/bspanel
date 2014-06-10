<?
$variants = $app->models->get($value->related_model())->find_all()->as_array("_id","name");

echo \Door\Core\Helper\Form::select("{$name}[]", $variants, $value->get_ids(), array(
			"class" => "populate placeholder select2"
		));