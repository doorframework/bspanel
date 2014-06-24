<?

echo \Door\Core\Helper\Form::input($name, date('d.m.Y', $value), array(
	'placeholder' => $app->lang->get($name),
	'class' => 'form-control datepicker'
));