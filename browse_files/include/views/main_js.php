<script>
//<![CDATA[
$(document).ready(function() {

	var timer;
	var timeOut = 300; // delay after last keypress to execute filter

	// filter
	$('.filter-field input').keyup(function(event) {

		clearTimeout(timer); // if we pressed the key, it will clear the previous timer and wait again
		timer = setTimeout(function() {

		    var search_value =  $('.filter-field input').val().toLowerCase();
		    var zebra = 'odd';

			$('table.file-list tr').each(function(index) {
				
				var contents = $(this).children('td.filename').find('a').html().toLowerCase();
				
				if (contents.indexOf(search_value) !== -1 || $(this).hasClass('back-button')){
					$(this).show().removeClass('odd').removeClass('even').addClass(zebra);
					if (zebra == 'odd') zebra = 'even'; else zebra = 'odd';
				}else{
					$(this).hide();
				}
			});

		} , timeOut);

	});

	// images preview button
	if ($('table.file-list a[rel="lightbox[images]"]').length > 0) {
		$('a.view-style').show();
	} 
		
	$('a.view-style').click(function(){
		$('a.image-size').show();
		$('a.view-style').remove();
		$('table.file-list a[rel="lightbox[images]"]').each(function(index) {
			var img_src = $(this).attr('href');
			$(this).prepend('<img src="'+img_src+'" class="image-preview">');

		});
	});

	$('.mobile-small-btn.add').click(function(){
		$('#newfolder_button').show();
		$(this).remove();
	});

	$('.breads').click(function(){
		$('.breadcrumbs').show();
		$(this).remove();
	});
	
	$('a.image-size.increase').click(function(){
		var width = $('table.file-list a[rel="lightbox[images]"] img').width();
		width = width + 20 + 'px';
		$('table.file-list a[rel="lightbox[images]"] img').css('width', width);
	});
	$('a.image-size.decrease').click(function(){
		var width = $('table.file-list a[rel="lightbox[images]"] img').width();
		width = width - 20 + 'px';
		$('table.file-list a[rel="lightbox[images]"] img').css('width', width);
	});
	
	

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload();
    	
    $('#fileupload').fileupload('option', {
        url: '<?php echo gatorconf::get('base_url')?>/?upload=1',
        maxFileSize: <?php echo gatorconf::get('max_filesize')?>,
        maxNumberOfFiles: <?php echo gatorconf::get('max_files')?>,
        downloadTemplateId: false,
		autoUpload: true,
        acceptFileTypes: <?php echo gatorconf::get('accept_file_extensions')?>
    });

 	// clear checkboxes
	$('td input[type="checkbox"]').each( function() {$(this).attr("checked", null);});

    $(document).foundationButtons();

 	// Hover shim for Internet Explorer 6 and Internet Explorer 7.
    $(document).on('hover','a',function(){
        $(this).toggleClass('hover');
    });

    $('#new-folder').click(function(){
    	$('#newfolder').css('border-color', '#CCCCCC');
    });

 	// user edit
 	$('body').on('click', '.username-edit', function() {

    	$('#modal').html(' ');

 		var output = '<div class="modal-content"><h5><?php echo lang::get("Change password")?></h5><hr />';
 		output += '<h5><?php echo lang::get("New password:")?></h5><input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />';
 		output += '<h5><?php echo lang::get("Confirm password:")?></h5><input type="password" name="password2" id="password2" value="" class="text ui-widget-content ui-corner-all" />';

 		output += '</div>';

 		output += '<div class="modal-buttons right">';
 		output += '<button id="confirm-button" type="button" class="nice radius button"><?php echo lang::get("Change")?></button>';
 		
		output += '</div>';
 		
 		output += '<a class="close-reveal-modal"></a>';
 		
 		$('#modal').append(output);
 		$('#second_modal').hide();
 		$('#modal').reveal();

 		$('#confirm-button').click(function(){

 			$('#password').css('border-color', '#CCCCCC');
 			$('#password2').css('border-color', '#CCCCCC');
 			
			var password = $('#password').val();
			var password2 = $('#password2').val();

			if(typeof(password) === 'undefined' || password == ''){
				$('#password').css('border-color', 'red');
				return false;
			}
			
			if(password != password2){
				$('#password2').css('border-color', 'red');
				return false;				
			}

			password_data = encodeURIComponent(password);
			
			$.post("<?php echo gatorconf::get('base_url')?>/", { changepassword: password_data} ).done(function(data) {
				// flush 
				window.location.href = '<?php echo gatorconf::get('base_url')?>/';
			});
			


	    });

    });
    // open on passwword recovery
    <?php if (gatorconf::get('enable_password_recovery') && isset($_SESSION['directlinkenter']) && $_SESSION['directlinkenter'] == 'passwordrecovery'):?>
    <?php $_SESSION['directlinkenter'] = 'done';?>
	$('a.username-edit').trigger('click');
    <?php endif;?>
    
    $('#new-folder').click(function(){
    	$('#newfolder').css('border-color', '#CCCCCC');
    });
    
 	// mobile sign out & password
    $('.mobile-actions.sign-out').click(function(){
        
    	$('#second_modal').html(' ');

 		var output = '<div class="modal-content"><h5><?php echo $_SESSION['simple_auth']['username']?></h5><hr />';
 	
 		output += '</div>';

 		output += '<div class="modal-buttons right">';

 		<?php if(gatorconf::get('allow_change_password')):?>
 		output += '<button type="button" class="nice radius button username-edit"><?php echo lang::get("Change password")?></button>';
 		<?php endif;?>

 		output += '<a href="?logout=1"><button type="button" class="nice radius button"><?php echo lang::get("Sign out")?></button></a>';
 		
		output += '</div>';
 		
 		output += '<a class="close-reveal-modal"></a>';
 		
 		$('#second_modal').append(output);
 		$('#second_modal').reveal();
    });
    
    // new folder
    $('a.new-folder').click(function(){
    	
    	var name = $('input#newfolder').val();

    	if(typeof(name) === 'undefined' || name == ''){
    		$('#newfolder').css('border-color', 'red');
    		return false;
		}
		
    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?newdir='+name;

    });

    // new file
    $('a.new-file').click(function(){
    	
    	var name = $('input#newfolder').val();

    	if(typeof(name) === 'undefined' || name == ''){
    		$('#newfolder').css('border-color', 'red');
    		return false;
		}
		
    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?newfile='+name;

    });

    // submit new folder on enter key
    $('input#newfolder').keypress(function(e){
	    	if(e.keyCode == 13){
	    		var name = $('input#newfolder').val();
	        	window.location.href = '<?php echo gatorconf::get('base_url')?>/?newdir='+name;
	   		 return false;
		   	 }else{
		   		 return true;
		   	 }
    });

    // show tree
    $('.directory-tree').click(function(){
    	showDirectoryTree('<?php echo lang::get("Folder Structure")?>', '/?tree=cd');
    });
    
    // toggle selection
    $('.select-button').click(function(){
    	if ($('button.select-button').html() == '<?php echo lang::get("Unselect All")?>'){
    		$('td input[type="checkbox"]').each( function() {$(this).attr("checked", null);});
    		$('button.select-button').html('<?php echo lang::get("Select All")?>');
    	
    	}else{
    		$('td input[type="checkbox"]').each( function() {$(this).attr("checked", status);});
    		$('button.select-button').html('<?php echo lang::get("Unselect All")?>');
    	}
    });

    // finish with upload
    $('.upload-done').click(function(){

        var progress = $('.uploading');

        if ($(progress).size()!=0){

        	$('#modal').html(' ');

    		var output = '<div class="modal-content"><h3><?php echo lang::get("Upload in progress...")?></h3><hr /><h5><?php echo lang::get("Do you want to cancel upload?")?></h5></div>';

    		output += '<div class="modal-buttons right">';
    		output += '<button id="stop-button" type="button" class="nice radius button"><?php echo lang::get("Stop Upload")?></button>';
    		output += '</div>';
    		
    		output += '<a class="close-reveal-modal"></a>';
    		
    		$('#modal').append(output);
    		$('#modal').reveal();

    		$('#stop-button').click(function(){
    			window.location.href = '<?php echo gatorconf::get('base_url')?>';
    	    });

        	return;
        }
        
    	window.location.href = '<?php echo gatorconf::get('base_url')?>';
    });

    // cut / copy / paste
    $('.cut-selected').click(function(){
    	submitAction('cut', true);
    });
    $('.copy-selected').click(function(){
    	submitAction('copy', true);
    });
    $('.paste-selected').click(function(){

    	submitAction('paste', false);

       	$('#modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');
       	$('#modal').reveal({
        	     animation: 'none', //fade, fadeAndPop, none
        	     animationspeed: 0 //how fast animations are
       	});
       	$('body *').unbind();


    });
    $('.simple-copy-selected').click(function(){
    	// notice if nothing is selected
    	if (isSelected() == false) return false;
    	
    	showDirectoryTree('<?php echo lang::get("Select Destination Folder")?>', '/?tree=copy', 'simple-copy');
    });
    $('.simple-move-selected').click(function(){
    	// notice if nothing is selected
    	if (isSelected() == false) return false;
    	
    	showDirectoryTree('<?php echo lang::get("Select Destination Folder")?>', '/?tree=move', 'simple-move');
    });

    // zip selected
    $('.zip-selected').click(function(){
        
    	// notice if nothing is selected
    	if (isSelected() == false) return false;
    	
    	$('#modal').html(' ');

		var output = '<div class="modal-content"><h5><?php echo lang::get("Add to archive:")?></h5><input type="text" name="archive-name" id="archive-name" value="archive.zip" class="text ui-widget-content ui-corner-all" /></div>';

		output += '<div class="modal-buttons right">';
		output += '<button id="confirm-button" type="button" class="nice radius button"><?php echo lang::get("Create Zip")?></button>';
		output += '<button id="cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
		output += '</div>';
		
		output += '<a class="close-reveal-modal"></a>';
		
		$('#modal').append(output);
		$('#modal').reveal();

		$('#cancel-button').click(function(){
			$('#modal').trigger('reveal:close');
	    });
	    
		$('#confirm-button').click(function(){

			var archive_name = $('#archive-name').val();

    		if (archive_name == ''){
    			$('#modal').trigger('reveal:close');
        		 return;
    		}
    		
			$('<input>').attr({
			    type: 'hidden',
			    name: 'archivename',
			    value: archive_name
			})
			.appendTo('form#fileset');

			$('<input>')
			.attr({
			    type: 'hidden',
			    name: 'action',
			    value: 'zip'
			})
			.appendTo('form#fileset');
			
	    	$('#modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');
	    	$('form#fileset').submit();
			$('body *').unbind();
	    });

    });

    // delete selected
    $('.delete-selected').click(function(){

    	// notice if nothing is selected
    	if (isSelected() == false) return false;

    	$('#modal').html(' ');
    	var output = '<p class="lead"><?php echo lang::get("Are you sure you want to delete selected items?")?></p><hr />';
    	output += '<div class="modal-buttons right">';
    	output += '<button id="confirm-button" type="button" class="nice alert radius button"><?php echo lang::get("Delete")?></button>';
    	output += '<button id="cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
    	output += '</div>';
    	output += '<a class="close-reveal-modal"></a>';

    	$('#modal').append(output);
    	$('#modal').reveal();
    	
	    $('#cancel-button').click(function(){
	    	$('#modal').trigger('reveal:close');
	    });

	    $('#confirm-button').click(function(){

	    	$('<input>').attr({
			    type: 'hidden',
			    name: 'action',
				value: 'delete'
			}).appendTo('form#fileset');

	    	$('#modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');	
	    			
			$('form#fileset').submit();
			$('body *').unbind();
	    });

	    return;
    });

    // sorting
    $('.sort-invert').click(function(){
    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?sortinvert';	
    });
    $('.sort-by-name').click(function(){
    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?sortby=name';	
    });
    $('.sort-by-date').click(function(){
    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?sortby=date';	
    });
    $('.sort-by-size').click(function(){
    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?sortby=size';	
    });


    // single file settings - buttons & actions
    $('body').on('click', '.action-info', function() {
    	var permissions = '<?php echo $_SESSION['simple_auth']['permissions']?>';
    	var allow_links = '<?php echo gatorconf::get('allow_file_links')?>';

    	var data_type = $(this).attr('data-type');
    	var data_link = $(this).attr('data-link');
		var data_name = $(this).attr('data-name');
		var data_crypt = $(this).attr('data-crypt');
		var data_size = $(this).attr('data-size');
		var data_time = $(this).attr('data-time');
    	
    	$('#modal').html(' ');

   		var output = '<div class="modal-descr"><h4>'+data_name+'</h4><hr />';
    
    	// if links are not disabled and not dir
    	if (allow_links != '' && data_type != 'dir'){
    		output += '<div class="modal-content"><h5><?php echo lang::get("Download Link:")?></h5><input type="text" name="link" id="link" value="'+data_link+'" class="text ui-widget-content ui-corner-all" readonly="readonly" /></div>';
    	}

    	output += '<div class="modal-buttons right">';

    	// download sub-button
    	if (data_type != 'dir'){
    		output += '<button id="download-button" type="button" class="nice radius button"><?php echo lang::get("Download")?></button>';

    		// email button
    		<?php if (gatorconf::get('allow_email_links')):?>
        	output += '<button id="email-button" type="button" class="nice radius button"><?php echo lang::get("Email")?></button>';
        	<?php endif;?>
    	}
    	
    	// rename & unzip sub-buttons - if we have write permissions
    	if (permissions.indexOf("w") != -1){

    		<?php if (gatorconf::get('use_zip')):?>
        	if (data_type == 'zip'){
        		output += '<button id="unzip-button" type="button" class="nice radius button"><?php echo lang::get("Unzip")?></button>';	
        	}
        	<?php endif;?>

        	<?php if (gatorconf::get('allow_edit_files')):?>
        	if (data_type == 'generic'){
        		output += '<button id="edit-button" type="button" class="nice radius button"><?php echo lang::get("Edit")?></button>';	
        	}
        	<?php endif;?>

        	<?php if (gatorconf::get('allow_rename_files')):?>
    		output += '<button id="rename-button" type="button" class="nice radius button"><?php echo lang::get("Rename")?></button>';
    		<?php endif;?>	
    	}
    
    	// cancel sub-button
    	output += '<button id="cancel-button" type="button" class="nice radius button"><?php echo lang::get("Close")?></button>';
    	output += '</div>';
    	output += '<a class="close-reveal-modal"></a>';
    	
    	$('#modal').append(output);
    	$('#modal').reveal();

    	$('#cancel-button').click(function(){
        	$('#modal').trigger('reveal:close');
        });

    	$('#download-button').click(function(){
    		window.location.href = '<?php echo gatorconf::get('base_url')?>/?download='+data_crypt;
        });


    	<?php if (gatorconf::get('allow_email_links')):?>
    	$('#email-button').click(function(){

    		$('#second_modal').html(' ');

    		var output = '<div class="modal-content"><h5><?php echo lang::get("Enter an email:")?></h5><input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all" />';

    		output += '<h5><?php echo lang::get("Additional message or remark:")?></h5><textarea id="email-content" cols="50" rows="5"></textarea>';
    		output += '<div class="modal-buttons right">';
    		output += '<button id="confirm-button" type="button" class="nice radius button"><?php echo lang::get("Send email")?></button>';
    		output += '<button id="second-cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
    		output += '</div>';
    		
    		output += '<a class="close-reveal-modal"></a>';
    		
    		$('#second_modal').append(output);
    		$('#second_modal').reveal();

    		$('#second-cancel-button').click(function(){
    			$('#second_modal').trigger('reveal:close');
    	    });

    		$('#confirm-button').click(function(){

    			var email = $('#email').val();
    			var email_content = $('#email-content').val();

    			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    			
    			if (!filter.test(email)) {
    				$('#email').css('border-color', 'red');
    				return false;
    			}

        		if (email == ''){
        			$('#second_modal').trigger('reveal:close');
            		 return;
        		}
    			
    			$('<input>')
    			.attr({
    			    type: 'hidden',
    			    name: 'filelink',
    			    value: data_link					    
    			})
    			.appendTo('form#fileset');
    			
    			$('<input>').attr({
    			    type: 'hidden',
    			    name: 'email',
    			    value: email
    			})
    			.appendTo('form#fileset');

    			$('<input>').attr({
    			    type: 'hidden',
    			    name: 'email_content',
    			    value: email_content
    			})
    			.appendTo('form#fileset');

    			$('<input>')
    			.attr({
    			    type: 'hidden',
    			    name: 'action',
    			    value: 'email'
    			})
    			.appendTo('form#fileset');

    			$('#second_modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');
    			$('form#fileset').submit();
    			$('body *').unbind();
    	    });
        });
        <?php endif;?>
        

    	$('#unzip-button').click(function(){

    		$('#second_modal').html(' ');

    		var output = '<div class="modal-content"><h5><?php echo lang::get("Unzip archive content here?")?></h5></div><hr />';

    		output += '<div class="modal-buttons right">';
    		output += '<button id="confirm-button" type="button" class="nice radius button"><?php echo lang::get("Unzip")?></button>';
    		output += '<button id="second-cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
    		output += '</div>';
    		
    		output += '<a class="close-reveal-modal"></a>';
    		
    		$('#second_modal').append(output);
    		$('#second_modal').reveal();

    		$('#second-cancel-button').click(function(){
    			$('#second_modal').trigger('reveal:close');
    	    });
    	    
    		$('#confirm-button').click(function(){
    			$('<input>').attr({
    			    type: 'hidden',
    			    name: 'filename',
    			    value: data_crypt
    			})
    			.appendTo('form#fileset');

    			$('<input>')
    			.attr({
    			    type: 'hidden',
    			    name: 'action',
    			    value: 'unzip'
    			})
    			.appendTo('form#fileset');
    			
    	    	$('#second_modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');
    			$('form#fileset').submit();
    			$('body *').unbind();
    	    });
    		
        });

    	$('#rename-button').click(function(){

    		$('#second_modal').html(' ');

    		if (data_type == 'dir'){
    			var itemt = '<?php echo lang::get("Folder")?>';
    		}else{
        		var itemt = '<?php echo lang::get("File")?>';
    		}
    		
    		var output = '<div class="modal-content"><h5><?php echo lang::get("Rename")?> '+itemt+':</h5><input type="text" name="new-name" id="new-name" value="'+data_name+'" crypt="'+data_crypt+'" class="text ui-widget-content ui-corner-all" /></div>';

    		output += '<div class="modal-buttons right">';
    		output += '<button id="confirm-button" type="button" class="nice radius button"><?php echo lang::get("Rename")?></button>';
    		output += '<button id="second-cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
    		output += '</div>';
    		
    		output += '<a class="close-reveal-modal"></a>';
    		
    		$('#second_modal').append(output);
    		$('#second_modal').reveal();

    		$('#second-cancel-button').click(function(){
    			$('#second_modal').trigger('reveal:close');
    	    });

    		$('#confirm-button').click(function(){

    			var newname = $('#new-name').val();

        		if (newname == ''){
        			$('#second_modal').trigger('reveal:close');
            		 return;
        		}
    			
    			$('<input>')
    			.attr({
    			    type: 'hidden',
    			    name: 'oldname',
    			    value: data_crypt					    
    			})
    			.appendTo('form#fileset');
    			
    			$('<input>').attr({
    			    type: 'hidden',
    			    name: 'newname',
    			    value: newname
    			})
    			.appendTo('form#fileset');

    			$('<input>')
    			.attr({
    			    type: 'hidden',
    			    name: 'action',
    			    value: 'rename'
    			})
    			.appendTo('form#fileset');

    			$('form#fileset').submit();
    	    });
        });

    	$('#edit-button').click(function(){
        	
    		$('#big_modal').html(' ');

    		var output = '<textarea id="file-content" cols="50" rows="25"></textarea>';

    		output += '<div class="modal-buttons right">';
    		output += '<button id="save-button" type="button" class="nice radius button"><?php echo lang::get("Save")?></button>';
    		output += '<button id="second-cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
    		output += '</div>';
    		
    		output += '<a class="close-reveal-modal"></a>';
    		
    		$('#big_modal').append(output);
    		
    		$.get('<?php echo gatorconf::get('base_url')?>/?edit-load='+data_crypt, function(data) {
    			  $('textarea#file-content').val(data);
    			});
			
    		$('#big_modal').reveal({ closeOnBackgroundClick: false});

    		$('#second-cancel-button').click(function(){
    			$('#big_modal').trigger('reveal:close');
    	    });

    		$('#save-button').click(function(){
    			var content = $('textarea#file-content').val();
    		
    			$('<input>').attr({
    			    type: 'hidden',
    			    name: 'filename',
    			    value: data_crypt
    			})
    			.appendTo('form#fileset');
    			
    			$('<input>').attr({
    			    type: 'hidden',
    			    name: 'content',
    			    value: content
    			})
    			.appendTo('form#fileset');

    			$('<input>')
    			.attr({
    			    type: 'hidden',
    			    name: 'action',
    			    value: 'edit-save'
    			})
    			.appendTo('form#fileset');

    			$('form#fileset').submit();
    		});
        });

    	return;
    });


    // account settings
    $('.action-account').click(function(){

    	var access = '<?php echo $_SESSION['simple_auth']['username']?>';
    	if (access != 'admin') return;

    	var main_repository = "<?php if (!gatorconf::get('mask_repository_path')) echo gatorconf::get('repository')?>";

    	var data_username = $(this).attr('data-username');

    	if (data_username == '-1'){
        	var is_new = 'yes';
        	var password_fieldname = '<?php echo lang::get("Password")?>';
    		var data_homedir = main_repository;
    		var data_email = '';
    		var data_permissions = 'rwu';
        	
    	}else{
    		var is_new = '';
	    	var password_fieldname = '<?php echo lang::get("Change password")?>';
			var data_homedir = $(this).attr('data-homedir');
			var data_email = $(this).attr('data-email');
			var data_permissions = $(this).attr('data-permissions');
    	}

		var p_read = '';
		var p_write = '';
		var p_upload = '';
		
		if(data_permissions.indexOf("r") != -1) p_read = 'checked="checked"';
		if(data_permissions.indexOf("w") != -1) p_write = 'checked="checked"';
		if(data_permissions.indexOf("u") != -1) p_upload = 'checked="checked"';
		
    	$('#modal').html(' ');

   		var output = '<div class="modal-content">';

   		if (data_username == 'admin' || data_username == 'guest'){
   			output += '<h5>'+data_username+' - system user</h5><hr />';
   		}else if (is_new == 'yes'){
   			output += '<h5><?php echo lang::get("Create New User")?></h5><hr />';
   		}else{
   			output += '<h5>'+data_username+'</h5><hr />';
   		}
    
    	if (is_new == 'yes'){
    		output += '<h5><?php echo lang::get("Username")?>:</h5><input type="text" name="username" id="username" value="" class="text ui-widget-content ui-corner-all" />';
    	}

    	if (data_username != 'guest'){
    		output += '<h5>'+password_fieldname+':</h5><input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />';
    		output += '<h5><?php echo lang::get("Email")?>:</h5><input type="text" name="email" id="email" value="'+data_email+'" class="text ui-widget-content ui-corner-all" />';
    	}
    	
    	if (data_username != 'admin'){
    		output += '<h5><?php echo lang::get("Home Directory:")?></h5><input type="text" name="homedir" id="homedir" value="'+data_homedir+'" class="text ui-widget-content ui-corner-all" />';
    		output += '<h5><?php echo lang::get("Permissions:")?></h5><input type="checkbox" '+p_read+' id="read"><?php echo lang::get("Read")?> <input type="checkbox" '+p_write+' id="write"><?php echo lang::get("Write")?> <input type="checkbox" '+p_upload+' id="upload"><?php echo lang::get("Upload")?>';
    	}
    	
    	output += '</div>';
    	
    	output += '<div class="modal-buttons right">';
    	output += '<button id="save-button" type="button" class="nice radius button"><?php echo lang::get("Save User")?></button>';
    	output += '</div>';
    	
    	output += '<a class="close-reveal-modal"></a>';
    	
    	$('#modal').append(output);
    	$('#modal').reveal();

    	// no spaces here
    	$("#username, #password").on("keydown", function (e) {
    	    return e.which !== 32;
    	});
    	
    	$('#save-button').click(function(){

    		if (is_new == 'yes'){
    			data_username = $('#username').val();
     		}

    		if(typeof(data_username) === 'undefined' || data_username == ''){
        		$('#username').css('border-color', 'red');
        		return false;
    		}
    		
			var password = $('#password').val();
			if(typeof(password) !== 'undefined') set_password = encodeURIComponent(password);
			
			if (data_username == 'guest'){
				set_password = '';
			}
		
			var homedir = $('#homedir').val();
			var email = $('#email').val();
			var read = $('#read').prop('checked');
			var write = $('#write').prop('checked');
			var upload = $('#upload').prop('checked');

			data_username = encodeURIComponent(data_username);
			
			$.post("<?php echo gatorconf::get('base_url')?>/?account="+data_username, { is_new: is_new, homedir: homedir, email: email, read: read, write: write, upload: upload, password: set_password} ).done(function(data) {
				// flush 
				window.location.href = '<?php echo gatorconf::get('base_url')?>/';
			});

	    });

    	return;
    });


    // delete account
    $('.action-delete-account').click(function(){

    	var data_username = $(this).attr('data-username');
    	
    	$('#modal').html(' ');
    	var output = '<p class="lead"><?php echo lang::get("Are you sure you want to delete this user?")?></p><hr />';
    	output += '<div class="modal-buttons right">';
    	output += '<button id="confirm-button" type="button" class="nice alert radius button"><?php echo lang::get("Delete")?></button>';
    	output += '<button id="cancel-button" type="button" class="nice radius button"><?php echo lang::get("Cancel")?></button>';
    	output += '</div>';
    	output += '<a class="close-reveal-modal"></a>';

    	$('#modal').append(output);
    	$('#modal').reveal();
    	
	    $('#cancel-button').click(function(){
	    	$('#modal').trigger('reveal:close');
	    });

	    $('#confirm-button').click(function(){
	    	window.location.href = '<?php echo gatorconf::get('base_url')?>/?account='+data_username+'&delete=yes';
	    });

	    return;
    });
  
});
function isSelected(){
	if ($('td input[type="checkbox"]:checked').is(":empty") == false){
		
		$('#modal').html(' ');
		$('#modal').append('<p class="lead"><?php echo lang::get("Use checkboxes to select items.")?></p><hr />');
		$('#modal').append('<button id="cancel-button" type="button" class="nice radius button right"><?php echo lang::get("Close")?></button>');
		$('#modal').append('<a class="close-reveal-modal"></a>');
		$('#modal').reveal();
		
	    $('#cancel-button').click(function(){
	    	$('#modal').trigger('reveal:close');
	    });

		return false;
	}
	return true;
}
function submitAction(action, checkSelection, destination){
	// notice if nothing is selected
	if (checkSelection == true && isSelected() == false) return false;

	if (destination){
		$('<input>').attr({
		    type: 'hidden',
		    name: 'destination',
			value: destination
		}).appendTo('form#fileset');
	}
	
	$('<input>').attr({
	    type: 'hidden',
	    name: 'action',
		value: action
	}).appendTo('form#fileset');
	
	$('form#fileset').submit();
}
function showDirectoryTree(title, ajaxcall, post_action){
	
	$('#big_modal').html(' ');

	var output = '<p class="lead text-search-info">'+title+'</p>';
	output += '<hr />';
	
	output += '<p id="dir-links" class="lead"><?php echo lang::get("Please wait...")?></p>';
	output += '<p id="dir-links-backup" style="display:none"></p>';
	
	output += '<hr /><div class="text-search-box"><span class="search-img"></span><input type="text" id="text-search" class="inputtext" placeholder="<?php echo lang::get("Search and Filter")?>" />';
	output += '</div>';

	output += '<a class="close-reveal-modal"></a>';
	
	$('#big_modal').append(output);
	$('#big_modal').reveal();

	// get data via ajax
	$.get('<?php echo gatorconf::get('base_url')?>'+ajaxcall, function(data) {
			$('#dir-links').html(data);
			$('#dir-links-backup').html(data);

			bindTreeLinks(post_action);
	});

	// case-insensitive search extension (jQuery 1.8+)
	jQuery.expr[':'].Contains = jQuery.expr.createPseudo(function(arg) {
      return function( elem ) {
	    return ( elem.textContent || elem.innerText || getText( elem ) ).toUpperCase().indexOf(arg.toUpperCase()) >= 0;
	      };
    });
		
	// search and filter
	$('#text-search').bind('keyup change', function() {

    	// pull in the new value
        var searchTerm = $('#text-search').val();

        // reset from backup
        var backup =  $('#dir-links-backup').html();
		$('#dir-links').html(backup);

        // disable filter if empty
        if (searchTerm) {

			var match = $("#dir-links li:Contains('"+searchTerm+"')");

			// remove unwanted
			$(match).each(function(){
				$(this).find("li:not(:Contains('"+searchTerm+"'))").remove();
			});

			// remove double
			match = $(match).first();
			
			if ($(match).length == 0){
				$('#dir-links').html('<p><?php echo lang::get("Nothing found...")?></p>');
				return;
			}

			// load the whole thing
			var html = $('<div>').append($(match).clone()).remove().html();
			$('#dir-links').html(html);
        }
        
        bindTreeLinks(post_action);
    });
  
}
function bindTreeLinks(post_action){
	$('#dir-links li[clink]').click(function(e){
	
		var isfile = $(this).attr('isfile');

		if (typeof isfile != "undefined"){
			return false;
		}
		
		// stop overlapping li
		e.stopPropagation();
		
		var link = $(this).attr('clink');

		// this is post action / form submit?
		if (post_action){
			submitAction(post_action, false, link);

	    	$('#big_modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');
	       	$('body *').unbind();
	       	
			return;
		}

		// this is get, change dir
		window.location.href = '<?php echo gatorconf::get('base_url')?>/'+link;

		return;

    });
}
//]]>
</script>