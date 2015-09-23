<body class="main">

<!-- prepare upload templates -->
<?php gator::display("main_tmpl.php")?>

<?php if(gatorconf::get('use_auth') == true && gatorconf::get('show_top_auth_bar') == true && $_SESSION['simple_auth']['username'] != 'guest'):?>
<div class="top-menu">
<div class="row">

 <?php if(gatorconf::get('allow_change_password')):?>
 	<a class="username-edit"><?php echo $_SESSION['simple_auth']['username']?></a>
 <?php else:?>
	 <?php echo $_SESSION['simple_auth']['username']?>
 <?php endif;?>
 | <a href="?logout=1"><?php echo lang::get("Sign out")?></a>
</div>
</div>
<div class="top-menu-spacer"></div>
<?php endif;?>

<div id="wrapper" class="row">
<div class="container twelve columns">
<div id="topcorners"></div>
<div id="content">

<?php if(gatorconf::get('use_auth') == true && gatorconf::get('show_top_auth_bar') == false && $_SESSION['simple_auth']['username'] != 'guest'):?>
<div class="small-auth-menu">
 
 <?php if(gatorconf::get('allow_change_password')):?>
 	<a class="username-edit"><?php echo $_SESSION['simple_auth']['username']?></a>
 <?php else:?>
	 <?php echo $_SESSION['simple_auth']['username']?>
 <?php endif;?>
 
 | <a href="?logout=1"><?php echo lang::get("Sign out")?></a>
</div>
<?php endif;?>

<?php if(gatorconf::get('use_auth') == true && $_SESSION['simple_auth']['username'] == 'guest'):?>
<div class="small-auth-menu">
 <a href="?login=1"><?php echo lang::get("Sign in")?></a>
</div>
<?php endif;?>

<div id="logo">
<!--<a href="<?php echo gatorconf::get('base_url')?>/?cd="><img alt="filegator" src="<?php echo gatorconf::get('base_url')?>/include/views/img/logo.gif"></a>-->
</div>

<div class="fileupload-container navigation-button">
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="#" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="nav fileupload-buttonbar">
                
					
				<?php if (gator::checkPermissions('ru')):?>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="fileinput-button nice radius button">
                    <i class="icon-plus icon-white"></i>
					
					<span class=""><?php echo lang::get("Add Files...")?></span>

                    <input type="file" name="files[]" multiple>
                    <input type="hidden" name="uniqid" value="50338402749c1">
                </span>
                <?php endif;?>
                
                <div class="clear"></div>
                
        </div>
        
        <!-- The table listing the files available for upload/download -->
		<div id="top-panel">
        <table role="presentation" class="table table-striped">
         <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
         </tbody>
        </table>
        
        </div>
    </form>
</div>

<?php if (gator::checkPermissions('rw')):?>          
<div id="newfolder_button" class="navigation-button-right">
 <input type="text" class="inputtext" name="newfolder" id="newfolder">

 <div class="nice radius button split dropdown navigation-button-right">
  <a class="new-folder"><?php echo lang::get("New Folder")?></a>
  <span></span>
  <ul>
	<li><a class="new-folder"><?php echo lang::get("Create New Folder")?></a></li>
    <li><a class="new-file"><?php echo lang::get("Create New File")?></a></li>
  </ul>
</div>

</div>
<?php endif;?>
                
<div id="close-top-panel" class="clear">
<button type="button" class="nice radius button right upload-done"><?php echo lang::get("Done")?></button>
</div>

<div id="browse-panel">

<div class="breadcrumbs">
<span>
<?php foreach($params['breadcrumb'] as $key => $value):?>
<?php if($key != 'Home') echo '&raquo;&nbsp;'?>
<a href="<?php echo $value?>"><?php echo $key?></a>
<?php endforeach;?>
</span>
</div>

<div class="filter-field">
    <div class="row">
     <div class="twelve columns">
      <div class="row collapse">
       <div class="nine mobile-three columns">
        <input type="text">
       </div>
       <div class="three mobile-one columns">
        <span class="postfix"></span>
       </div>
      </div>
     </div>
	</div>
</div>

<a class="directory-tree"></a>

<a class="mobile-small-btn sort" data-reveal-id="modal-mobile-sort" style="display:none"></a>
<a class="mobile-small-btn breads" style="display:none"></a>
<?php if (gator::checkPermissions('rw')):?>
<a class="mobile-small-btn add" style="display:none"></a>
<?php endif;?>
<a class="view-style" style="display:none"></a>
<a class="image-size decrease" style="display:none"></a>
<a class="image-size increase" style="display:none"></a>

<div class="clear"></div>

<form id="fileset" action="?" method="POST" accept-charset="UTF-8">

<?php gator::display("main_filelist.php", $params)?>

<div class="bottom-actions">
<?php if (gator::checkPermissions('rw')):?>
<button type="button" class="nice radius button select-button"><?php echo lang::get("Select All")?></button>

<div class="selection-buttons">
	<?php if (gatorconf::get('simple_copy_move')):?>
	<button type="button" class="nice secondary radius button simple-copy-selected"><?php echo lang::get("Copy")?></button>
	<button type="button" class="nice secondary radius button simple-move-selected"><?php echo lang::get("Move")?></button>
	<?php else:?>
	<button type="button" class="nice secondary radius button cut-selected"><?php echo lang::get("Cut")?></button>
	<button type="button" class="nice secondary radius button copy-selected"><?php echo lang::get("Copy")?></button>
	<button type="button" class="nice secondary radius button paste-selected"<?php if (!isset($_SESSION['buffer'])) echo ' disabled="disabled"'?>"><?php echo lang::get("Paste")?></button>
	<?php endif;?>
	
	<?php if (gatorconf::get('use_zip')):?>
	<button type="button" class="nice secondary radius button zip-selected"><?php echo lang::get("Zip")?></button>
	<?php endif;?>
	<button type="button" class="nice secondary radius button delete-selected"><?php echo lang::get("Delete")?></button>
</div>

<?php endif;?>
<div class="nice radius button split dropdown up right desktop sort-button">
  <a class="sort-invert"><?php echo lang::get("Sort by")?> <?php echo lang::get(ucfirst($_SESSION['sort']['by']))?></a>
  <span></span>
  <ul>
    <li><a class="sort-by-name"><?php echo lang::get("Sort by Name")?></a></li>
    <li><a class="sort-by-date"><?php echo lang::get("Sort by Date")?></a></li>
    <li><a class="sort-by-size"><?php echo lang::get("Sort by Size")?></a></li>
  </ul>
</div>
</div>

</form>
</div> <!-- end browse-panel -->
</div>
<div id="bottomcorners"></div>

</div>
</div>

<div class="mobile-nav">
<div class="row">
 <div class="container twelve columns">
 	<?php if (gator::checkPermissions('rw')):?>
	<a class="mobile-actions select-all select-button"></a>
	<a class="mobile-actions selection" data-reveal-id="modal-mobile"></a>
	<?php endif;?>
	<a class="mobile-actions sort" data-reveal-id="modal-mobile-sort"></a>
	
	<?php if(gatorconf::get('use_auth') == true && $_SESSION['simple_auth']['username'] != 'guest'):?>
	<a class="mobile-actions sign-out"></a>
	<?php endif;?>
	
	<?php if(gatorconf::get('use_auth') == true && $_SESSION['simple_auth']['username'] == 'guest'):?>
	 <a class="mobile-actions sign-in" href="?login=1"></a>
	<?php endif;?>
 </div>
</div>
</div>

<div id="modal" class="reveal-modal"></div>
<div id="second_modal" class="reveal-modal"></div>
<div id="big_modal" class="reveal-modal large"></div>

<div id="modal-mobile" class="reveal-modal">
<a class="close-reveal-modal"></a><br/>
<div class="bottom-actions-mobile">
<div class="selection-buttons">
	<?php if (gatorconf::get('simple_copy_move')):?>
	<button type="button" class="nice secondary radius button simple-copy-selected"><?php echo lang::get("Copy")?></button>
	<button type="button" class="nice secondary radius button simple-move-selected"><?php echo lang::get("Move")?></button>
	<?php else:?>
	<button type="button" class="nice secondary radius button cut-selected"><?php echo lang::get("Cut")?></button>
	<button type="button" class="nice secondary radius button copy-selected"><?php echo lang::get("Copy")?></button>
	<button type="button" class="nice secondary radius button paste-selected"<?php if (!isset($_SESSION['buffer'])) echo ' disabled="disabled"'?>"><?php echo lang::get("Paste")?></button>
	<?php endif;?>
	
	<?php if (gatorconf::get('use_zip')):?>
	<button type="button" class="nice secondary radius button zip-selected"><?php echo lang::get("Zip")?></button>
	<?php endif;?>
	<button type="button" class="nice secondary radius button delete-selected"><?php echo lang::get("Delete")?></button>
</div>
</div>
</div>

<div id="modal-mobile-sort" class="reveal-modal">
<a class="close-reveal-modal"></a><br/><br/>
<div class="nice radius button split dropdown right sort-button">
  <a class="sort-invert"><?php echo lang::get("Sort by")?> <?php echo lang::get(ucfirst($_SESSION['sort']['by']))?></a>
  <span></span>
  <ul>
    <li><a class="sort-by-name"><?php echo lang::get("Sort by Name")?></a></li>
    <li><a class="sort-by-date"><?php echo lang::get("Sort by Date")?></a></li>
    <li><a class="sort-by-size"><?php echo lang::get("Sort by Size")?></a></li>
  </ul>
</div>
<br/><br/><br/>
</div>

<?php gator::display("main_js.php")?>