<body class="main">

<?php if(!gatorconf::get('use_database') && !is_writable(gatorconf::get('user_config_file'))) echo 'Notice: cannot write to user config file: '.gatorconf::get('user_config_file');?>

<?php if(gatorconf::get('use_auth') == true && gatorconf::get('show_top_auth_bar') == true && $_SESSION['simple_auth']['username'] != 'guest'):?>
<div class="top-menu">
<div class="row">
 <?php echo $_SESSION['simple_auth']['username']?>
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
 <?php echo $_SESSION['simple_auth']['username']?>
 | <a href="?logout=1"><?php echo lang::get("Sign out")?></a>
</div>
<?php endif;?>

<?php if(gatorconf::get('use_auth') == true && $_SESSION['simple_auth']['username'] == 'guest'):?>
<div class="small-auth-menu">
 <a href="?login=1"><?php echo lang::get("Sign in")?></a>
</div>
<?php endif;?>

<div id="logo">
<a href="<?php echo gatorconf::get('base_url')?>/?cd="><img alt="filegator" src="<?php echo gatorconf::get('base_url')?>/include/views/img/logo.gif"></a>
</div>

<button data-username="-1" class="action-account nice radius button" type="button"><?php echo lang::get("Add New User")?></button>
&nbsp;&nbsp;
<a href="<?php echo gatorconf::get('base_url')?>/?export=csv" class="action-export nice radius secondary button" type="button">Export data</a>

<br /><br />

<div id="browse-panel" style="overflow: auto;">

<p>Main Repository: <?php echo gatorconf::get('repository')?></p>

<table class="file-list">
	<tbody>
	<?php foreach ($params as $user):?>
	<?php if ($user['username'] == 'guest' && !gatorconf::get('allow_guests')) continue;?>
	<?php if (gatorconf::get('mask_repository_path')) $user['homedir'] = str_replace(gatorconf::get('repository'), '', $user['homedir'])?>
		<tr class="accounts" >
			<td class="username">
			 	<?php echo $user['username']?>
			</td>
			<td class="homedir">
			 	<?php if(isset($user['homedir']) && $user['username'] != 'admin') echo $user['homedir']?>
			</td>
			<td class="permissions">
			 	<?php if($user['username'] != 'admin')echo $user['permissions']?>
			</td>
			<td class="actions accounts">
			 <button type="button" class="action-account" data-username="<?php echo $user['username']?>" data-password="<?php echo $user['password']?>" data-permissions="<?php echo $user['permissions']?>" data-homedir="<?php echo $user['homedir']?>" data-email="<?php if(isset($user['email'])) echo $user['email']?>"></button>
			 <?php if ($user['username'] != 'admin' && $user['username'] != 'guest'):?>
			 	<button type="button" class="action-delete-account" data-username="<?php echo $user['username']?>" ></button>
			 <?php endif;?>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>

</div> <!-- end browse-panel -->
</div>

<div id="bottomcorners"></div>
</div>
</div>

<div class="mobile-nav">
<div class="row">
 <div class="container twelve columns">
	<a href="?logout=1" class="mobile-actions sign-out"></a>
 </div>
</div>
</div>

<div id="modal" class="reveal-modal"></div>
<div id="second_modal" class="reveal-modal"></div>
<div id="big_modal" class="reveal-modal large"></div>


<?php gator::display("main_js.php")?>