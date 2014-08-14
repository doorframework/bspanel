<?
use Door\Bootstrap\Helper\Icons;

/* @var $app \Door\Core\Application */

$l = $app->lang;
$h = $app->html;
?>
<h1><?=$title?>&nbsp;
</h1>
<br/><br/>
<? if(isset($errors)) { ?>
<p class="bg-warning"><?=$l->get_ucf("bad_data")?></p>
<? } ?>
<? if(isset($success)) { ?>
<p class="bg-success"><?=$l->get_ucf("saved")?></p>
<? } ?>
<div class="box">
	<div class="box-content">
		<form role="form" class="form-horizontal" method="POST" action="">		
			<?=$fields?>
			<div class="clearfix"></div>
			<div class="text-right">
				<button class="btn btn-primary btn-label-left" type="submit">
					<?=Icons::fa('save')?>
					<?=$l->get_ucf("save")?>
				</button>
			</div>
		</form>
	</div>	
</div>