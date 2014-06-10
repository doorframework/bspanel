$(function(){

	var upload_field = null;
	var upload_submit = null;
	var upload_form = null;
	var upload_start_callback = null;
	var upload_finish_callback = null;
	var busy = false;
	
	var initUploads = function(){
		
		if(upload_form != null) return;
		
		//var iframe = $('<iframe name="photoframe" style="height: 0; overflow: hidden;"></iframe>');
		//$('body').append(iframe);
		upload_form = $('<form name="upload_form" method="POST" style="height: 0; overflow: hidden;" action="/admin/upload_images" enctype="multipart/form-data"></form>');
		upload_field = $('<input name="image[]" type=file multiple accept="image/jpeg, image/gif, image/png"/>');
		upload_submit = $('<input type=submit value="submit form" />');
				
		upload_form.append(upload_field);
		upload_form.append(upload_submit);		
		$('body').append(upload_form);					
		
		upload_field.on('change', function(){
			
			if(upload_start_callback != null){
				upload_start_callback();
			}
			
			busy = true;		
			
			upload_form.ajaxSubmit({				
				success: function(ids){
					busy = false;
					if(ids.length > 0){
						//images_list_field.val(images_list_field.val() + "," + ids);
						upload_finish_callback(ids);									
					} else {
						alert("Ошибка при загрузке фотографии. Попробуйте уменьшить размер файла и повторить попытку.");
						upload_finish_callback("");
						//upload_button.show();
						//upload_wait.hide();										
					}		
				}
			});
			
		});
		
	}
	
	

	var showUpload = function(multiple){
		
		if(busy)return;
		initUploads();
		
		if(multiple == true){
			upload_field.attr('multiple','multiple');
		} else{
			upload_field.removeAttr('multiple');
		}
		
		upload_field.trigger('click');				
		
	}
	
	
	var multiple_uploader = $(".image-gallery");
	if(multiple_uploader.length > 0){
		
		multiple_uploader.each(function(){			
			
			var multiple_uploader = $(this);			
			var upload_button = multiple_uploader.find('button.upload');
			var images_list_field = multiple_uploader.find('input[type=hidden]');
			var photo_list = multiple_uploader.find('.box-content');
			var upload_wait = multiple_uploader.find('.upload-wait');						
			
			var sortUpdate = function(){	

				var image_ids = [];
				photo_list.children().each(function(index){
					image_ids.push($(this).data('image-id'));			
				});
				images_list_field.val(image_ids.join(","));
			}

			var refreshImagesList = function(){
				if(busy) return;
				$.post("/admin/view_images",{images:images_list_field.val()},function(response){
					photo_list.html(response);
					photo_list.sortable();
					photo_list.on('sortupdate',sortUpdate);
					upload_button.show();
					upload_wait.hide();						
				});
			}

			photo_list.on('click','i',function(){
				if(confirm('Удалить фотографию?')){
					$(this).parent().remove();		
					sortUpdate();
				}
			});

			refreshImagesList();			
						
			upload_button.click(function(){				
				upload_start_callback = function(){
					upload_button.hide();
					upload_wait.show();												
				}				
				upload_finish_callback = function(ids){
					
					images_list_field.val(images_list_field.val() + "," + ids);
					refreshImagesList();
					upload_button.show();
					upload_wait.hide();								
				}				
				showUpload(true);			
				return false;				
			});
		});
	}
	
	
	
	
	$('form .image-input').each(function(){
		var wrap = $(this);
		var btn = wrap.find('button.upload');
		var hidden = wrap.find('input[type=hidden]');
		var img_holder = wrap.find('.image-block');
		
		img_holder.on('click','i', function(){
			if(confirm("Удалить фотографию?"))
			{
				hidden.val("");
				refreshImg();
			}
		});
		
		//console.log(wrap,btn,hidden,img_holder);
		
		var refreshImg = function(){
			var img_id = hidden.val();
			if(img_id.length !== 24){
				
				img_holder.html('<img src="/media/images/no_image.png"/>');
				
			} else {
				
				$.post("/admin/view_images",{image_id:img_id},function(response){
					img_holder.html(response);			
				});				
				
			}
		}
		
		btn.click(function(){
			
			var oldBtnVal = btn.html();
			
			upload_start_callback = function(){
				btn.attr('disabled','disabled');
				btn.html("Загрузка...");									
			};				
			upload_finish_callback = function(id){
				btn.removeAttr('disabled');
				btn.html(oldBtnVal);													
				hidden.val(id);
				refreshImg();
			};				
			showUpload(false);				
			return false;
		});
		
		
		refreshImg();
	});
	
	
	/*			<div class="wrap photo-single-uploader">
				<label for="lab_url">Логотип</label>
				<span class="image">
					<?=Form::hidden('image_id', $user->image_id)?>
					<div class="img">
						<img src="/media/images/no_image.png"/>
					</div>					
					<button class="btn-send">Загрузить логотип</button>
				</span>
			</div>	*/
	
	
});