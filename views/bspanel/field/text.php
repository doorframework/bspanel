<?

echo \Door\Core\Helper\Form::input($name, $value, array(
	'placeholder' => $app->lang->get($name),
	'class' => 'form-control'
));