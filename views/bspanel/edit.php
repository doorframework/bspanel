<?
use Door\Bootstrap\Helper\Icons;

/* @var $app \Door\Core\Application */

$l = $app->lang;
$h = $app->html;
?>
<h1><?=$title?>&nbsp;<?

	if(isset($return_uri) && $return_uri !== null)
	{
		echo $h->anchor($return_uri, Icons::glyphicon('backward')." ".$l->get_ucf("back"), array('class' => 'btn btn-default'));
	}
	
?>
</h1>
<bv/><br/>
<? if(isset($errors)) { ?>
<p class="bg-warning"><?=$l->get_ucf("bad_data")?></p>
<? } ?>
<? if(isset($success)) { ?>
<p class="bg-success"><?=$l->get_ucf("saved")?></p>
<? } ?>
<div class="box">
	<div class="box-content">
<form role="form" class="form-horizontal" method="POST" action="">		
	<?
		if(isset($filter_param) && $filter_param != null)
		{
			echo \Door\Core\Helper\Form::hidden($filter_param, $model->$filter_param);
		}
	?>
	<?=$fields?>
	<div class="clearfix"></div>
	<div class="text-right">
		<div class="col-sm-12">
		<? if(isset($return_uri)) { ?>
			<a class="btn btn-default btn-label-left" href="<?=$app->url->site($return_uri)?>">
				<?=Icons::fa('backward')?>
				<?=$l->get_ucf('back')?>
			</a>		
			&nbsp;
		<? } ?>
		<button class="btn btn-primary btn-label-left" type="submit">
			<?=Icons::fa('save')?>
			<?=$l->get_ucf("save")?>
		</button>
		</div>
	</div>
</form>
	</div>
</div>