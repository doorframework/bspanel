<?
$variants = array();
foreach($app->models->factory($value->related_model())->find_all()->as_array() as $model)
{
	$variants[(string)$model->pk()] = $model->name();
}
$ids = $value->get_ids(true);

$sorted = array();
$unsorted = array();

foreach($variants as $id => $variant_name)
{
	$index = array_search($id, $ids);
	if($index === false)
	{
		$unsorted[$id] = $variant_name;
	}
	else
	{
		$sorted[$index] = array($id, $variant_name);
	}
}

ksort($sorted);

$new_variants = array();
foreach($sorted as $item)
{
	$new_variants[$item[0]] = $item[1];
}
foreach($unsorted as $id => $variant_name)
{
	$new_variants[$id] = $variant_name;
}

echo \Door\Core\Helper\Form::select("{$name}[]", $new_variants, $ids, array(
			"class" => "populate placeholder select2"
		));