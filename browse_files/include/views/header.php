<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" >
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="file gator" >
	<title>Browse Files</title>
	<!--<link rel="shortcut icon" href="<?php echo gatorconf::get('base_url')?>/include/views/img/favicon.ico" />-->

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
	
	<!-- jQuery -->
	<script type="text/javascript" src="<?php echo gatorconf::get('base_url')?>/include/jquery/jquery-1.8.0.min.js"></script>
	
	<!-- Blueimp -->
	<link rel="stylesheet" href="<?php echo gatorconf::get('base_url')?>/include/blueimp/css/jquery.fileupload-ui.css">
	<link rel="stylesheet" href="<?php echo gatorconf::get('base_url')?>/include/blueimp/css/style.css">
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/vendor/jquery.ui.widget.js"></script>
	<!-- The Templates plugin is included to render the upload/download listings -->
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/tmpl.min.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/jquery.fileupload.js"></script>
	<!-- The File Upload file processing plugin -->
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/jquery.fileupload-fp.js"></script>
	<!-- The File Upload user interface plugin -->
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/jquery.fileupload-ui.js"></script>
	<!-- The localization script -->
	<script src="<?php echo gatorconf::get('base_url')?>/include/blueimp/js/locale.js"></script>
	
	<!-- Lightbox2 -->
	<?php if(gatorconf::get('use_lightbox_gallery')):?>
	<script type="text/javascript" src="<?php echo gatorconf::get('base_url')?>/include/lightbox/lightbox.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo gatorconf::get('base_url')?>/include/lightbox/lightbox.css">
	<?php endif;?>
	
	<!-- Foundation3 -->
	<link rel="stylesheet" href="<?php echo gatorconf::get('base_url')?>/include/foundation/stylesheets/foundation.css">
	<script src="<?php echo gatorconf::get('base_url')?>/include/foundation/javascripts/jquery.foundation.buttons.js"></script>
	<script src="<?php echo gatorconf::get('base_url')?>/include/foundation/javascripts/jquery.foundation.reveal.js"></script>
	<script src="<?php echo gatorconf::get('base_url')?>/include/foundation/javascripts/responsive-tables.js"></script>
	
	<!-- FileGator styles -->
	<link rel="stylesheet" href="<?php echo gatorconf::get('base_url')?>/include/views/style.css">
	
	<!-- Mobile breakpoint -->
	<style type="text/css">
	@media only screen and (max-width: <?php echo gatorconf::get('mobile_breakpoint')?>px) {
		#fileset td {
			line-height: 50px;
		}
		
		div#content {
			background: none;
		}
		
		.search-img, 
		.bottom-actions, 
		#topcorners,
		#bottomcorners,
		.small-auth-menu,
		.top-menu { display: none !important; }
			
		body { background-color: white; margin: 0 }
		.container { padding:0; }
		#content { padding: 15px !important }
		.top-menu-spacer { display: none }
		
		.fileinput-button { float: none; }
		
		.mobile-nav .columns {
			padding: 22px;
		}
		
		.mobile-nav { 
			display: block;
			width: 100%;
			height: 80px;
			background-color: #E9E9E9;
			border-top: 2px solid #C3C3C3;
			position: fixed;
			bottom: 0;
		}
		
		.login .container {
			padding: 20px;
		}
		
		div#header {
			margin-bottom: 0;
			padding: 0;
		}
		
		table tr {
			height: 58px !important;
		}
	}
	</style>
	

</head>
