<?
/* @var $app Door\Core\Application */
use Door\Core\Helper\Form as F;
$l = $app->lang;

?>
<div class="form-group">
	<label class="col-sm-2 control-label"><?=$l->get($name)?></label>
	<div class="col-sm-10"><?=$field?></div>
</div>	