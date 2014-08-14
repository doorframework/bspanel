<?

$attributes = array(
	'placeholder' => $app->lang->get($name),
	'class' => 'form-control'
);

if(isset($field_config['height']))
{
	$attributes['style'] = "height:{$field_config['height']}px;";
}

echo \Door\Core\Helper\Form::textarea($name, $value, $attributes);