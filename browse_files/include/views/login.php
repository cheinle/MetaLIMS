
<body class="login">
<div id="wrapper">
<div id="header">
</div>

<div class="container">
<div id="topcorners"></div>

<div id="content" class="login">
<div id="logo">
<!--<a href="<?php echo gatorconf::get('base_url')?>"><img alt="filegator" src="<?php echo gatorconf::get('base_url')?>/include/views/img/logo.gif"></a>-->
</div>

<?php if (isset($params['errors'])):?>
<div class="error">
<?php echo $params['errors'];?>
</div>
<?php endif;?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?login=1">

<div>
<p><strong><?php echo lang::get("Username")?></strong></p>
<input id="username" name="username" class="inputtext" type="text"></div>
<div>

<p><strong><?php echo lang::get("Password")?></strong></p>
<input class="inputtext" type="password" name="password" id="password">
<?php if (gatorconf::get('enable_password_recovery')):?>
<a id="password-recovery" class="right" href="#"><?php echo lang::get("Forgotten Password?")?></a><div class="clear"></div>
<?php endif;?>
</div>

<div>
<input type="hidden" name="submit" value="ie_enter_fix">
<input class="nice radius button" type="Submit" name="submit" value="<?php echo lang::get("Sign in")?>">

<?php if (gatorconf::get('allow_signup')):?>
<input class="nice radius secondary button" style="float:left;" type="Submit" value="<?php echo lang::get("Sign up")?>" onclick="window.location='<?php echo gatorconf::get('base_url')?>/?signup=1&'; return false;"> 
<?php endif;?>



</div>

</form>


</div>
<div id="bottomcorners"></div>
</div>
</div>

<?php if (gatorconf::get('enable_password_recovery')):?>
<div id="modal" class="reveal-modal"></div>
<div id="second_modal" class="reveal-modal"></div>
<div id="big_modal" class="reveal-modal large"></div>

<script>
//<![CDATA[
$(document).ready(function() {
	// recover password
    $('#password-recovery').click(function(){

    	$('#modal').html(' ');

 		var output = '<div class="modal-content"><h5><?php echo lang::get("Forgotten Password?")?></h5><hr />';
 		output += '<h5><?php echo lang::get("Email")?></h5><input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />';

 		output += '</div>';

 		output += '<div class="modal-buttons right">';
 		output += '<button id="confirm-button" type="button" class="nice radius button"><?php echo lang::get("Done")?></button>';
 		
		output += '</div>';
 		
 		output += '<a class="close-reveal-modal"></a>';
 		
 		$('#modal').append(output);
 		$('#modal').reveal();

 		$('#confirm-button').click(function(){

 			$('#email').css('border-color', '#CCCCCC');

 			var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
			var email = $('#email').val();

			if(typeof(email) === 'undefined' || email == '' || !pattern.test(email)){
				$('#email').css('border-color', 'red');
				return false;
			}

			$('#modal').html('<p class="lead"><?php echo lang::get("Please wait...")?></p>');
			
			usersemail = encodeURIComponent(email);
			
			$.post("<?php echo gatorconf::get('base_url')?>/?recover_password=1", { emaildata: usersemail} ).done(function(data) {
				// show ok

				$('#second_modal').html(' ');
				var output = '<div class="modal-content"><h5><?php echo lang::get("Please open your email and click on the link to proceed.")?></h5><hr />';
				output += '<button id="cancel-button" type="button" class="nice radius button right"><?php echo lang::get("Close")?></button>';
				output += '</div>';
				output += '<a class="close-reveal-modal"></a>';
				
				$('#second_modal').append(output);
				$('#second_modal').reveal();

				$('#cancel-button').click(function(){
					$('#second_modal').trigger('reveal:close');
			    });

			});

	    });

    });

    $('#email-button').click(function(){

		
    });
});    
//]]>
</script>
<?php endif;?>