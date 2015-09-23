<?php
/**
 *
 * Configuration options
 *
 * You may need to logout/login for some changes to take effect
 *
 * Make sure Apache service has read/write access to the repository and config folder(s)
 *
 * NOTICE: Current .htaccess file inside repository directory will prevent script and html execution inside that directory
 *
 */

class gatorconf {


	public static function get($param) {

		$config = array(

				// language selection, available languages: english, french, polish, portuguese and serbian
				'language' => 'english',
			
				// mobile layout breakpoint in pixels. Set to 99999 for mobile-only layout
				'mobile_breakpoint' => 667,

				// user config file
				// for more that 20 users please enable database storage below
				'user_config_file' => getcwd().'/config/config.json',

				// use database to store users? (true/false)
				'use_database' => false,
				'db_host' => 'localhost',
				'db_username' => 'myUser',
				'db_password' => 'myPassword',
				'db_database' => 'myDatabase',

				// main file repository
				// this is also a repository for users without specific homedir
				// you'll need to make sure your webserver can write here
				'repository' => getcwd().'/repository',
				
				// maximum file size in bytes when uploading
				// The php.ini settings upload_max_filesize and post_max_size
				// take precedence over the following setting
				//'max_filesize' => 2097152, // 2MB
				'max_filesize' => 209715200, // This is 200 Megabytes

				// allow users to sign up (true/false)
				'allow_signup' => false,
				
				// use signup activation via email (true/false)
				'signup_use_activation' => true,

				// default permissions given to the users after initial signup
				// notation: 'r' - read only, 'ru' - read & upload, 'rwu' - read, write & upload
				'default_permissions_after_signup' => 'r',
				
				// permissions given to the users after email activation
				// notation: 'r' - read only, 'ru' - read & upload, 'rwu' - read, write & upload
				'default_permissions_after_activation' => 'rwu',
				
				// email configuration
				'mail_from' => 'info@example.com',
				'mail_from_name' => 'info',
				'mail_signature' => "\n\nBest Regards,\nThe Team",
				
				// use smtp? (true/false)
				// if false php mail() will be used
				'use_smtp_protocol' => false,
				
				// smtp mail protocol settings
				'mail_smtp_host' => 'smtp.example.com:587',
				'mail_smtp_username' => 'info@example.com',
				'mail_smtp_password' => 'mypassword',
				'mail_smtp_connection_security' => 'tls', // 'tls', 'ssl' or '' for no security
				'mail_smtp_debug' => false,

				// recovery/activation email subject and link text
				'account_email_subject' => 'Action Required',
				'account_email_text' => 'Please click on the link below to proceed to your account: ',

				// users can change their password (true/false)
				'allow_change_password' => true,
				
				// enable forgotten password procedure (true/false)
				'enable_password_recovery' => false,
				
				// allow clickable links on files (true/false)
				'allow_file_links' => true,

				// if your repository is outside filegator's main folder you need to set this
				// this will serve as a base url/directory for all direct links
				// example: 
				// 'direct_links_baseurl' => 'http://example.com/drupal',
				// 'direct_links_repository' => '/var/www/html/drupal',
				'direct_links_baseurl' => '',
				'direct_links_basedir' => '',
			
				// allow links to be sent via email (true/false)
				'allow_email_links' => false,
				'mail_link_subject' => 'File for you',

				// Use goo.gl URL shortener (true/false). This requires cURL php support.
				'use_googl_shorturl' => false,
				// get goo.gl API key from: http://code.google.com/apis/console/
				'googl_shorturl_api_key' => '123456789',

				// use lightbox plugin to preview images (true/false)
				// this also enables allow_file_links
				'use_lightbox_gallery' => true,

				// accepted file types when uploading or '*' for no restrictions
				// example: 'accept_file_extensions' => array('gif','jpg','jpeg','png'),
				'accept_file_extensions' => array('*'),

				// max number of files on batch upload
				'max_files' => 100,

				// time/date format - see php date()
				'time_format' => 'd/m/y',

				// use simple copy-move instead of cut-copy-paste (true/false)
				'simple_copy_move' => true,

				// use zip functions (true/false)
				// zip extension must be enabled on server (see http://php.net/manual/en/book.zip.php)
				'use_zip' => true,

				// files can be edited (true/false)
				'allow_edit_files' => true,

				// files/folders can be renamed (true/false)
				'allow_rename_files' => true,

				// show top-bar (true/false)
				'show_top_auth_bar' => true,

				// this restricted files will be hidden (no wildcards)
				'restricted_files' => array('.htaccess'),
				
				// mask directories up to the main file repository (true/false)
				// set this to true if you don't want to use full path iside admin area
				'mask_repository_path' => false,

				// encrypt url actions (true/false)
				'encrypt_url_actions' => false,

				// allow guest account (true/false)
				'allow_guests' => false,
				
				// use authentication module (true/false)
				// WARNING: if you set this to false anyone can see, change or delete your files without need to login
				// this will also disable encrypt_url_actions
				'use_auth' => true,

				// server url and base path, usually you don't need to change this
				'base_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']),
				'base_path' => getcwd(),

				// write user actions to usage.log file (true/false)
				'write_log' => false,

		);

		/**
		 *
		 * End of configuration options
		 *
		*/

		$config['base_path'] = str_replace('\\', '/', $config['base_path']);
		if (class_exists('gator')) $config = gator::validateConf($config, $param);

		return $config[$param];
	}

}