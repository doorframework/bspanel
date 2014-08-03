$(function(){
	
	function MessagesMenuWidth(){
		var W = window.innerWidth;
		var W_menu = $('#sidebar-left').outerWidth();
		var w_messages = (W-W_menu)*16.666666666666664/100;
		$('#messages-menu').width(w_messages);
	}	
	
	$('.show-sidebar').on('click', function () {
		$('div#main').toggleClass('sidebar-show');
		setTimeout(MessagesMenuWidth, 250);
	});
	
	var height = window.innerHeight - 50;
	$('#main').css('min-height', height);	
	
	if(window.CKEDITOR)
	{
		$('textarea.wysiwyg').each(function(){
			CKEDITOR.replace(this,{
				extraPlugins : 'image,colorbutton,colordialog',
				filebrowserImageUploadUrl: $(this).data('upload-image-url')
			});
		});
	}
	
	$(".select2").each(function(){
		
		var select = $(this);
		select.select2Sortable({
			placeholder: "Выбор",
			bindOrder       : 'sortableStop',
			sortableOptions: {
				update: function(event, ui){
					console.log('sort',this);
				}
			}
		}).change(function(){
			console.log('update', this);
		});		
		
	});
		

	
	$('table.sortable-table').each(function(){
		
		var table = $(this);
		var tbody = table.find('tbody');
		tbody.sortable({
			update: function(){
				
				var ids = [];
				
				tbody.children().each(function(index, tr){
					ids.push($(tr).data('id'));
				});
				
				$.post(location.href,{
					'update_sort': 1,
					'ids': ids.join(',')
				});
			}
		});
		
	});
	
	$('.bspanel-tabs').tab();
	
	$('input.datepicker').datepicker({
		dateFormat : "dd.mm.yy"
	});
});
