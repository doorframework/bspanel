<?
	/* @var $app \Door\Core\Application */
?>
<? foreach($image_ids as $image_id) { ?>
<div class="thumb-image" data-image-id="<?=$image_id?>">
	<?=$app->image->render($image_id, $image_presentation);?>
	<i class="bg-danger"><span class="glyphicon glyphicon-remove"></span></i>
</div>
<? } ?>
