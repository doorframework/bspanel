<div class="image-input" data-upload-image-url="<?=$app->url->site($upload_image_uri)?>" data-view-image-url="<?=$app->url->site($view_images_uri)?>">
	<?=\Door\Core\Helper\Form::hidden($name, is_object($value) ? $value->pk() : $value);?>
	<button class="btn btn-label-left btn-info upload" type="button">
	<span><i class="fa fa-picture-o"></i></span>
		<?=$app->lang->get('upload')?>		
	</button>									
	<div class="image-block"></div>	
</div>