<?
/* @var $app \Door\Core\Application */
/* @var $model \Door\Core\Model */
/* @var $columns array */
/* @var $create_button boolean */
/* @var $return_uri string */
/* @var $buttons array */
/* @var $uri string */
$table_id = uniqid();

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
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="<?=$table_id?>">
					<thead>
						<tr>
							<? foreach($columns as $column) { ?>
							<th><?=$app->lang->get_ucf((string)$column)?></th>
							<? } ?>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
											
					</tbody>
					<tfoot>
						<tr>
							<? foreach($columns as $column) { ?>
							<th><?=$app->lang->get_ucf((string)$column)?></th>
							<? } ?>
							<th>&nbsp;</th>
						</tr>
					</tfoot>
				</table>
	</div>
</div>
<script>
$(function(){
	
	var table_id = '<?=$table_id?>';
	var table = $('#' + table_id);
	var uri = '<?=$uri?>';
	
	table.DataTable({
		ajax: {
			url: "/" + uri,
			data: {
				data: 1
			}
		}
	});
	
});
</script>