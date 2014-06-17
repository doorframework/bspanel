<?
	use Door\Bootstrap\Helper\Icons;
	$h = $app->html;
	
?>
<h1>Панель управления</h1>
<br/><br/>
<div class="container main-page-links">
                    <div class="row">
                        <div class="col-md-12 text-center">
							<? foreach($admin_menu as $item) { ?>
								<?=$h->anchor($item->uri,Icons::show($item->icon)."<br/>{$item->name}",array('class' => 'btn btn-primary btn-lg'))?>
							<? } ?>							
                        </div>
                    </div>
</div>