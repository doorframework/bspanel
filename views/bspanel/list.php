<?
/* @var $app \Door\Core\Application */
/* @var $model \Door\Core\Model */
/* @var $items array */
/* @var $create_button boolean */
/* @var $return_uri string */
/* @var $buttons array */
/* @var $uri string */

$l = $app->lang;
$h = $app->html;

?>
<h1>
	<?=$l->get($model->get_model_name().".list")?>
	<? 
	if(strlen($return_uri) > 0) { 
		echo $h->anchor($return_uri, '<span class="glyphicon glyphicon-backward"></span> '.$l->get("back"));
	} 
	?>
</h1>
<div class="container">
    <div class="row col-md-8 col-md-offset-1 custyle">
    <table class="table table-striped">
    <thead>    
        <tr>
			<? foreach($columns as $column) { ?>
			<th><?=$l->get($column)?></th>
			<? } ?>
            <th class="text-right">
				<?
					if($create_button)
					{
						echo $h->anchor($uri."/edit", '<span class="glyphicon glyphicon-circle_plus"></span> '.$l->get($model->get_model_name().".create"), array(
							'class' => 'btn btn-primary'
						));
					}
				?>
			</th>
        </tr>
    </thead>
	<? foreach($items as $item) { ?>
            <tr>
				<?
					$fields = $model->get_fields();
					foreach($columns as $column)
					{
						$val = $model->$column;
						if($fields[$column]['type'] == 'boolean')
						{
							$val = $val ? $l->get("Yes") : $l->get("No");
						}
						echo "<td>$val</td>";
					}
				?>
                <td class="text-right">
					<?
						foreach($buttons as $button)
						{
							$icon = htmlspecialchars($button->icon);
							$uri = str_replace("<id>", $item->pk(), $uri);
							echo $h->anchor($uri, 
									"<span class='glyphicon $icon'></span> {$l->get($button->name)}", 
									array('class' => 'btn btn-info btn-xs'));
						}
					?>					
				</td>
            </tr>
	<? } ?>
           
    </table>
    </div>
</div>