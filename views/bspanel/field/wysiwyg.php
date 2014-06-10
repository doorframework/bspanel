<?
echo \Door\Core\Helper\Form::textarea($name, $value, array(
	'placeholder' => $app->lang->get($name),
	'class' => 'wysiwyg'
));