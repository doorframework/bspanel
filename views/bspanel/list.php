<?
/* @var $app \Door\Core\Application */
/* @var $model \Door\Core\Model */
/* @var $items array */
/* @var $create_button boolean */
/* @var $return_uri string */
/* @var $buttons array */
/* @var $uri string */

use \Door\Bootstrap\Helper\Icons;

$l = $app->lang;
$h = $app->html;

?>
<div class="container">
    <div class="row col-md-12 custyle">
	<h1>
		<?=$title?>
		<? 
		if(strlen($return_uri) > 0) { 
			echo $h->anchor($return_uri, Icons::glyphicon('backward').' '.$l->get_ucf("back"),array('class' => 'btn btn-info'));
		} 
		?>
	</h1>				
    <table class="table table-striped <?=$sortable ? 'sortable-table' : ''?>" data-filter-param="<?=$filter_param?>" data-filter-value="<?=$filter_value?>">
    <thead>    
        <tr>
			<? if($sortable) { ?>
			<th>&nbsp;</th>
			<? } ?>
			<? foreach($columns as $column) { ?>
			<th><?
				if($column instanceof \Door\BSPanel\Data\IColumn) {
					
					echo $l->get_ucf($column->name());
					
				} else {
					echo $l->get_ucf($column);
				}
			
					?></th>
			<? } ?>
            <th class="text-right">
				<?
					if($create_button)
					{
						$uri_param = $filter_param == null ? "" : "?{$filter_param}=$filter_value";
						echo $h->anchor($uri."/edit".$uri_param, Icons::glyphicon('plus').' '.$l->get_ucf($model->get_model_name().".create"), array(
							'class' => 'btn btn-primary'
						));
					}
				?>
			</th>
        </tr>
    </thead>
	<? foreach($items as $item) { ?>
            <tr data-id="<?=$item->pk()?>">
				<? if($sortable) { ?>
				<td class="sortfield"><?= \Door\Bootstrap\Helper\Icons::fa('bars')?></td>
				<? } ?>				
				<?
					$fields = $model->get_fields();
					foreach($columns as $column)
					{								
						if($column instanceof \Door\BSPanel\Data\IColumn) {

							echo "<td>".$column->render($item)."</td>";
							
						} else {
							$val = $item->$column;
							if($fields[$column]['type'] == 'boolean')
							{
								$val = Icons::show($val ? "ok" : "remove");
							}
							if($fields[$column]['type'] == 'date')
							{
								$val = date('d.m.Y', $val);
							}						
							echo "<td>$val</td>";							
						}

					}
				?>
                <td class="text-right">
					<?
						foreach($buttons as $button)
						{
							echo $button->render($app, array("<id>" => $item->pk()));							
						}
					?>			
				</td>
            </tr>
	<? } ?>
           
    </table>
    </div>
</div>