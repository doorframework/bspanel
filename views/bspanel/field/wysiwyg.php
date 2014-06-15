<?
echo \Door\Core\Helper\Form::textarea($name, $value, array(
	'placeholder' => $app->lang->get($name),
	'class' => 'wysiwyg',
	'data-upload-image-url' => $app->url->site($upload_image_uri)
));