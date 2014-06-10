<div class="box">
	<div class="box-header">
		<button class="btn btn-label-left btn-info upload" type="button">
		<span><i class="fa fa-picture-o"></i></span>
			<?=$app->lang->get('Upload')?>
		</button>																		
	</div>
	<?=\Door\Core\Helper\Form::hidden($name, implode(",", $value->get_ids()));?>
	<div class="box-content"></div>
</div>