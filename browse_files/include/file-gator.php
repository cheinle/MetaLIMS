<?php
define('DS', '/');

class gator {

	/**
	 *
	 * main init
	 */
	public function init() {

		// test if file-upload ajax call
		if (isset($_GET['upload']) && $_GET['upload'] == '1' && gator::checkPermissions('ru')){
			$this->ajaxUpload();
		}
		
		// test if signup
		if (isset($_GET['signup']) && $_GET['signup'] == '1' && gatorconf::get('allow_signup')){
			$this->initSignup();
			die;
		}
		
		// use auth?
		if (gatorconf::get('use_auth') == true){
			$this->authenticate();
		}else{
			$_SESSION['simple_auth']['permissions'] = 'rwu';
			$_SESSION['simple_auth']['username'] = 'guest';
			$_SESSION['simple_auth']['cryptsalt'] = gatorconf::get('encryption_salt');
		}


		// admin login
		if ($_SESSION['simple_auth']['username'] == 'admin'){

			if (isset($_GET['export']) && $_GET['export'] == 'csv'){
				
				// output headers so that the file is downloaded rather than displayed
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=data.csv');
					
				// create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');
				
				$users = gator::getAllUsers();
				
				foreach ($users as $row) {
					unset($row['password']);
					fputcsv($output, $row);
				}
				
				die;
			}
			
			if (isset($_GET['account'])){
				$this->adminActions();
			}

			$config_users = gator::getAllUsers();

			gator::display("header.php");
			gator::display("admin.php", $config_users);
			gator::display("footer.php");
			exit;
		}


		// handle actions (copy, paste, delete, rename etc.)
		$this->actions();

		$this->changeDirectory();

		// get current directory file-list
		$listing = gator::getDirectoryList();

		gator::display("header.php");
		gator::display("main.php", $listing);
		gator::display("footer.php");

	}


	/**
	 *
	 * Sign up init
	 */
	public function initSignup() {

		// try to activate account?
		if (gatorconf::get('signup_use_activation') && isset($_GET['activate'])){

			$key = $_GET['activate'];
			$user = gator::getUser($key, 'akey');

			if ($user){
				gator::updateUser($user['username'], array('akey' => '', 'permissions' => gatorconf::get('default_permissions_after_activation')));
				$user['permissions'] = gatorconf::get('default_permissions_after_activation');
				$this->loginUser($user);
			}

			header('Location: '.gatorconf::get('base_url'));
			die;
		}

		$errors = null;

		if (!empty($_POST)){

			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$captcha = $_POST['captcha'];


			// requiered fields & validation
			if (	!isset($username)
					|| $username == ''
					|| !ctype_alnum($username)
					|| gator::getUser($username)
					|| gator::getUser($email, 'email')
					|| !isset($password)
					|| !isset($password2)
					|| !isset($_POST['captcha'])
					|| $password == ''
					|| $password != $password2
					|| filter_var($email, FILTER_VALIDATE_EMAIL) == false
					|| (int)$captcha != (int)$_SESSION['captcha_answer'])
			{
				if ($username && !ctype_alnum($username)) $_POST['username'] = '';
				if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) $_POST['email'] = '';
					
				$errors = lang::get("All fields requiered!");
			}

			if ($errors == false){
					
				$reloadaction = 'thanks';
					
				$activationkey = '';
					
				if (gatorconf::get('signup_use_activation')){

					$activationkey = sha1(mt_rand(10000,99999).time());
					$url = gatorconf::get('base_url').'/?signup=1&activate='.$activationkey;

					$subject = gatorconf::get('account_email_subject');
					$body = gatorconf::get('account_email_text'). "\n\n" .$url;
					$this->sendEmail($email, $subject, $body);

					$reloadaction = 'goactivate';
				}
					
				// homedir will be created based on username
				$homedir = gatorconf::get('repository').DS.$username;

				// if dir does not exist - try to create one
				if ($homedir != '' && !is_dir($homedir)){
					if (!mkdir($homedir, 0755, true)) {
						echo "ERROR: User's homedir cannot be created. Check permissions. DIR = ".$homedir; die;
					}
				}

				// add new user
				gator::addUser($username, array(
				'password' => $password,
				'permissions' => gatorconf::get('default_permissions_after_signup'),
				'homedir' => $homedir,
				'email' => $email,
				'akey' => $activationkey));

				// thanks on success or go activate!
				header('Location: '.gatorconf::get('base_url').'/?signup=1&'.$reloadaction);
				die;
			}

		}
		

		if (isset($_GET['thanks'])){

			// thanks on success or go activate!
			header('Location: '.gatorconf::get('base_url'));
			die;

		}elseif (isset($_GET['goactivate'])){

			gator::display("header.php");
			gator::display("signup.php", array('goactivate' => 1));

		}else{

			gator::display("header.php");
			gator::display("signup.php", array('errors' => $errors));

		}

		gator::display("footer.php");

	}


	/**
	 *
	 * get and post actions (router)
	 *
	 */
	public function actions(){

		// no read permissions?
		if (!gator::checkPermissions('r')){
			gator::writeLog('auth bad - no read access');
			gator::error(lang::get("Access Forbidden"));
			die;
		}

		// POST actions
		if (isset($_POST['action'])){

			$action = $_POST['action'];
			unset($_POST['action']);

			// actions with read & write permissions
			if (gator::checkPermissions('rw')){
				switch ($action){

					case 'delete':

						foreach ($_POST as $post_file){

							if (in_array($post_file, gatorconf::get('restricted_files'))) continue;

							$files[] = $this->filterInput($this->decrypt($post_file));
						}

						$this->deleteFiles($files, $_SESSION['cwd']);

						break;

					case 'rename':

						if (gatorconf::get('allow_rename_files') == false || !isset($_POST['oldname']) || !isset($_POST['newname'])) break;

						$oldname = $this->filterInput($this->decrypt($_POST['oldname']));
						$newname = $this->filterInput($_POST['newname']);

						if (in_array($oldname, gatorconf::get('restricted_files')) || in_array($newname, gatorconf::get('restricted_files'))) break;

						$this->renameFile($oldname, $newname);

						break;

					case 'edit-save':

						if (gatorconf::get('allow_edit_files') == false || !isset($_POST['filename'])) break;

						$filename = $this->filterInput($this->decrypt($_POST['filename']));
						$content = $_POST['content'];

						if (in_array($filename, gatorconf::get('restricted_files'))) break;

						file_put_contents($_SESSION['cwd'].DS.$filename, $content);

						gator::writeLog('edit file / save - '.$filename);

						break;

					case 'zip':

						if (!isset($_POST['archivename'])) break;
						$archive_name = $this->filterInput($_POST['archivename']);
						unset($_POST['archivename']);

						foreach ($_POST as $post_file){
							$files[] = $this->filterInput($this->decrypt($post_file));
						}

						$this->zipFiles($files, $archive_name);

						break;

					case 'unzip':

						if (!isset($_POST['filename'])) break;

						$filename = $this->filterInput($this->decrypt($_POST['filename']));

						$this->unzipFile($filename);

						break;

					case 'copy':

						foreach ($_POST as $post_file){
							$files[] = $this->filterInput($this->decrypt($post_file));
						}
						$this->pushToBuffer($files, 'copy');

						break;

					case 'cut':

						foreach ($_POST as $post_file){
							$files[] = $this->filterInput($this->decrypt($post_file));
						}
						$this->pushToBuffer($files, 'cut');

						break;

					case 'paste':

						$this->pasteFromBuffer();

						break;

					case 'simple-copy':
					case 'simple-move':

						// link to home dir is blank
						if (!isset($_POST['destination'])) $_POST['destination'] = '';

						$destination = $this->filterInput($this->decrypt($_POST['destination']), false);
						$destination = rawurldecode($destination);
						unset($_POST['destination']);

						foreach ($_POST as $post_file){
							$files[] = $this->filterInput($this->decrypt($post_file));
						}

						if ($action == 'simple-copy'){
							$this->copyFiles($files, $_SESSION['cwd'], gatorconf::get('repository').DS.$destination);
						}

						if($action == 'simple-move'){
							$this->moveFiles($files, $_SESSION['cwd'], gatorconf::get('repository').DS.$destination);
						}

						break;

					default:
						break;
				}
					
			}

			// actions with read only permissions
			if (gator::checkPermissions('r')){
				switch ($action){
					case 'email':

						if (gatorconf::get('allow_email_links') != true || !isset($_POST['filelink']) || !isset($_POST['email'])) break;

						$to = $_POST['email'];
						$subject = gatorconf::get('mail_link_subject');

						$link = filter_var($_POST['filelink'], FILTER_SANITIZE_STRING);

						$body = filter_var($_POST['email_content'], FILTER_SANITIZE_STRING);

						if (gatorconf::get('use_googl_shorturl')){
							$link = $this->shortUrl($link);
						}

						$body .= "\n\n".$link;

						$this->sendEmail($to, $subject, $body);

						break;
				}
			}

			// flush url
			header('Location: '.gatorconf::get('base_url'));
			die;
		}

		//
		// GET actions
		//

		// download file
		if (isset($_GET['download']) && !empty($_GET['download'])){

			$filename = $this->filterInput($this->decrypt($_GET['download']));

			if (in_array($filename, gatorconf::get('restricted_files'))) die;

			if (!file_exists($_SESSION['cwd'].DS.$filename)) die;

			// Set headers
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/octet-stream");
			header("Content-Transfer-Encoding: binary");

			// output file
			set_time_limit(0);
			$file = @fopen($_SESSION['cwd'].DS.$filename,"rb");
			while(!feof($file))
			{
				print(@fread($file, 1024*8));
				ob_flush();
				flush();
			}

			gator::writeLog('download - '.$filename);

			die;
		}

		// edit action - load file content via this ajax
		if (isset($_GET['edit-load']) && gator::checkPermissions('rw') && gatorconf::get('allow_edit_files') == true){

			$filename = $this->filterInput($this->decrypt($_GET['edit-load']));

			if (in_array($filename, gatorconf::get('restricted_files'))) die;

			if (!file_exists($_SESSION['cwd'].DS.$filename)) die;

			echo file_get_contents($_SESSION['cwd'].DS.$filename);

			gator::writeLog('edit file / load - '.$filename);

			die;
		}

		// new folder / new file
		if ((isset($_GET['newdir']) || isset($_GET['newfile'])) && gator::checkPermissions('rw')){

			$newdir = $newfile = '';

			if (isset($_GET['newdir']) && $_GET['newdir'] != ''){
				$newdir = $this->filterInput($_GET['newdir']);

				if (!in_array($newdir, gatorconf::get('restricted_files')))
					mkdir($_SESSION['cwd'].DS.$newdir, gatorconf::get('new_dir_mode'));

			} elseif (isset($_GET['newfile']) && $_GET['newfile'] != ''){
				$newfile = $this->filterInput($_GET['newfile']);

				if (!in_array($newfile, gatorconf::get('restricted_files')))
					touch($_SESSION['cwd'].DS.$newfile);
			}

			gator::writeLog('create new - '.$newdir.$newfile);

			// flush url
			header('Location: '.gatorconf::get('base_url'));
			die;
		}

		// sorting
		if (isset($_GET['sortby']) || isset($_GET['sortinvert'])){

			if (isset($_GET['sortby'])){
				$_SESSION['sort']['by'] = $this->filterInput($_GET['sortby']);
				$_SESSION['sort']['order'] = 1;
			} elseif (isset($_GET['sortinvert'])){
				$_SESSION['sort']['order'] *= -1;
			}

			gator::writeLog('sort order '.$_SESSION['sort']['by']);

			// flush url
			header('Location: '.gatorconf::get('base_url'));
			die;

		}elseif(!isset($_SESSION['sort']['by'])){
			$_SESSION['sort']['by'] = 'name';
			$_SESSION['sort']['order'] = 1;
		}

		// directory tree - ajax load
		if (isset($_GET['tree']) || !empty($_GET['tree'])){

			$tree_action = $this->filterInput($_GET['tree']);

			$dirs = '';

			if ($tree_action == 'cd'){
				$dirs = $this->getDirectoryTree(gatorconf::get('repository'), false, '?cd=');
			}

			if ($tree_action == 'copy' || $tree_action == 'move'){
				$dirs = $this->getDirectoryTree(gatorconf::get('repository'), true, '');
			}

			echo $dirs;

			gator::writeLog('tree load');
			die;
		}

		// change password
		if (gatorconf::get('allow_change_password') && isset($_POST['changepassword']) && !empty($_POST['changepassword'])){

			$new_password = rawurldecode($_POST['changepassword']);

			gator::updateUser($_SESSION['simple_auth']['username'], array('password' => $new_password));

			// flush url
			header('Location: '.gatorconf::get('base_url'));
			die;
		}

		return;
	}


	/**
	 *
	 * Admin updates users data
	 */
	public function adminActions(){


		$account = (isset($_GET['account']) ? rawurldecode($_GET['account']) : false);
		$password = (isset($_POST['password']) ? rawurldecode($_POST['password']) : false);
		$homedir = (isset($_POST['homedir']) ? $_POST['homedir'] : false);
		$email = (isset($_POST['email']) ? $_POST['email'] : false);
		$read = (isset($_POST['read']) && $_POST['read'] == 'true' ? 'r' : '');
		$write = (isset($_POST['write']) && $_POST['write'] == 'true' ? 'w' : '');
		$upload = (isset($_POST['upload']) && $_POST['upload'] == 'true' ? 'u' : '');
		$permissions = $read.$write.$upload;
		if ($account == 'admin') $permissions = 'rwu';
		
		if (gatorconf::get('mask_repository_path')) {
			$homedir = gatorconf::get('repository').DS.$homedir;

			// do not allow dirs up to reposiroty
			if (strstr($homedir, gatorconf::get('repository')) == false){
				$homedir = gatorconf::get('repository');
			}
		}

		// fix homedir slashes
		$homedir = rtrim($homedir, "/\\");
		$homedir = str_replace('\\', '/', $homedir);
		// remove consecutive dots and slashes
		$homedir = preg_replace('~\.\.+~', '/', $homedir);
		$homedir = preg_replace('~/+~', '/', $homedir);

		// delete user
		if (isset($_GET['delete']) && $_GET['delete'] == 'yes'){
			gator::deleteUser($account);
			header('Location: '.gatorconf::get('base_url'));
			die;
		}

		// new user account
		if (isset($_POST['is_new']) && $_POST['is_new'] == 'yes'){
			gator::addUser($account, array('password' => $password, 'permissions' => $permissions, 'homedir' => $homedir, 'akey' => '', 'email' => $email));
			return;
		}

		// update user
		gator::updateUser($account, array('password' => $password, 'permissions' => $permissions, 'homedir' => $homedir, 'email' => $email));

		return;
	}


	/**
	 *
	 * set current working directory
	 */
	public function changeDirectory(){

		// in no session or home - set defaults
		if (!isset($_SESSION['cwd']) || (isset($_GET['cd']) && $this->decrypt($_GET['cd']) == '')){
			$_SESSION['cwd'] = gatorconf::get('repository');

			gator::writeLog('change dir - home');

			return;
		}

		// get directory from url
		$input = (isset($_GET['cd']) ? $this->filterInput($this->decrypt($_GET['cd']), false) : false);

		if ($input && strpos($input, '..') === false){

			$childDir = gatorconf::get('repository').DS.$input;
			$childDir = str_replace('\\', '/', $childDir);

			// do not allow chdir outside reposiroty
			if (strstr($childDir, gatorconf::get('repository')) == false){
				header('Location: '.gatorconf::get('base_url'));
				die;
			}

			if (is_dir($childDir)){
				// change to dir if exists
				$_SESSION['cwd'] = $childDir;

				gator::writeLog('change dir - '.$input);
			}

		}

		return;
	}


	/**
	 *
	 * fix path to work with url
	 */
	public static function encodeurl($string){

		$string = rawurlencode($string);

		// do not encode /, :
		$string = str_replace("%2F", "/", $string);
		$string = str_replace("%5C", "/", $string);

		$string = str_replace("%3A", ":", $string);

		return $string;
	}


	/**
	 *
	 * filter user's input
	 */
	public function filterInput($string, $strict = true){

		// bad chars
		$strip = array("..", "*", "\n");

		// we need this sometimes
		if ($strict) array_push($strip, "/", "\\");

		$clean = trim(str_replace($strip, "_", strip_tags($string)));

		return $clean;
	}


	/**
	 *
	 * display views controller
	 */
	public static function display($view, $params = null){

		require_once gatorconf::get('base_path')."/include/views/".$view;
	}


	/**
	 *
	 * Delete file or dir
	 */
	public function deleteFiles($files, $directory){

		foreach ($files as $file){

			gator::writeLog('delete - '.$file);

			if ($file == '.' || $file == '..') continue;

			if (is_dir($directory.DS.$file) == true){
				$this->recursiveRemoveDirectory($_SESSION['cwd'].DS.$file);
				$this->syncBufferFile($_SESSION['cwd'].DS.$file);
				continue;
			}

			unlink($directory.DS.$file);

			$this->syncBufferFile($directory.DS.$file);
		}
	}


	/**
	 *
	 * Copy files or dirs
	 */
	public function copyFiles($files, $source_dir, $destination_dir){

		foreach($files as $file){

			// destination is not a source's subfolder?
			if(strpos($destination_dir.DS.$file, $source_dir.DS.$file.DS) !== false){
				continue;
			}

			$this->copyRecursively($source_dir.DS.$file, $destination_dir.DS.$file);
		}

	}

	/**
	 *
	 * Copy files recursively
	 */
	public function copyRecursively($source, $dest){

		// Simple copy for a file
		if (is_file($source)) {
			gator::writeLog('copy - '.$dest);
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Deep copy directories
			$this->copyRecursively($source.DS.$entry, $dest.DS.$entry);
			gator::writeLog('copy - '.$source.' > '.$dest);
		}

		// Clean up
		$dir->close();

		return true;
	}


	/**
	 *
	 * Move files or dirs
	 */
	public function moveFiles($files, $source_dir, $destination_dir){

		// batch move
		foreach($files as $file){

			if ($file == '.' || $file == '..' || in_array($file, gatorconf::get('restricted_files'))) return false;

			if (!file_exists($destination_dir.DS.$file)){

				// destination is not a source's subfolder?
				if(strpos($destination_dir.DS.$file, $source_dir.DS.$file.DS) !== false){
					continue;
				}

				rename($source_dir.DS.$file, $destination_dir.DS.$file);

				gator::writeLog('move - '.$file);

				$this->syncBufferFile($source_dir.DS.$file);
			}
		}
		return;
	}


	/**
	 *
	 * Reneme file or dir
	 */
	public function renameFile($old_name, $new_name){

		if (!file_exists($_SESSION['cwd'].DS.$new_name)){
			rename($_SESSION['cwd'].DS.$old_name, $_SESSION['cwd'].DS.$new_name);

			gator::writeLog('rename - '.$old_name.' > '.$new_name);

			$this->syncBufferFile($_SESSION['cwd'].DS.$old_name);
		}

		return;
	}


	/**
	 *
	 * Zip selected files
	 */
	public function zipFiles($input_files, $destination){

		if (!gatorconf::get('use_zip')) return;

		if (!extension_loaded('zip')) {
			gator::error('Zip PHP module is not installed on this server');
			die;
		}

		$destination = $_SESSION['cwd'].DS.$destination;
		if (substr($destination, -4, 4) != '.zip'){
			$destination = $destination.'.zip';
		}

		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			gator::error('Archive could not be created');
		}

		$startdir = str_replace('\\', '/', $_SESSION['cwd']);

		foreach ($input_files as $source){

			gator::writeLog('zip - '.$source);

			$source = $_SESSION['cwd'].DS.$source;

			$source = str_replace('\\', '/', $source);

			if (is_dir($source) === true)
			{
				$subdir = str_replace($startdir.'/', '', $source) . '/';
				$zip->addEmptyDir($subdir);
					

				$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

				foreach ($files as $file)
				{

					$file = str_replace('\\', '/', $file);

					// Ignore "." and ".." folders
					if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
						continue;

					if (is_dir($file) === true)
					{
						$zip->addEmptyDir($subdir . str_replace($source . '/', '', $file . '/'));
					}
					else if (is_file($file) === true)
					{
						$zip->addFile($file, $subdir . str_replace($source . '/', '', $file));
					}
				}
			}
			else if (is_file($source) === true)
			{
				$zip->addFile($source, basename($source));
			}
		}

		$zip->close();

		return;
	}


	/**
	 *
	 * UnZip archive
	 */
	public function unzipFile($file){

		if (!gatorconf::get('use_zip')) return;

		if (!extension_loaded('zip')) {
			gator::error('Zip PHP module is not installed on this server');
			die;
		}

		$file = $_SESSION['cwd'].DS.$file;

		$zip = new ZipArchive;
		if ($zip->open($file) === TRUE) {
		
			$entries = array();
			for ($idx = 0; $idx < $zip->numFiles; $idx++) {
				 $zname = $zip->getNameIndex($idx);
				if (strpos(implode("", gatorconf::get('restricted_files')), pathinfo($zname, PATHINFO_BASENAME)) !== false
				|| $zname == pathinfo($file, PATHINFO_BASENAME)) continue;
				$entries[] = $zname;
			}

			$zip->extractTo($_SESSION['cwd'].DS, $entries);
			$zip->close();
		} else {
			gator::error("Unable to proccess file '{$file}'");
		}

		return;
	}


	/**
	 *
	 * unset buffer if moving/deleting/renaming something inside buffer
	 */
	public function syncBufferFile($file){

		if (isset($_SESSION['buffer']['files'])){
			foreach($_SESSION['buffer']['files'] as $buffer_file){

				if(strpos($_SESSION['buffer']['directory'].DS.$buffer_file, $file) !== false){
					unset($_SESSION['buffer']);
					return;
				}
			}
		}
	}


	/**
	 *
	 * Mark if file is inside buffer, if not return false
	 */
	public function isBuffered($file){

		if (isset($_SESSION['buffer']['files'])){
			foreach($_SESSION['buffer']['files'] as $buffer_file){

				if($_SESSION['buffer']['directory'].DS.$buffer_file == $file){
					return ' buffer-'.$_SESSION['buffer']['type'];
				}
			}
		}

		return false;
	}


	/**
	 *
	 * Push files to buffer
	 */
	public function pushToBuffer($files, $action){

		$_SESSION['buffer']['files'] = $files;
		$_SESSION['buffer']['type'] = $action;
		$_SESSION['buffer']['directory'] = $_SESSION['cwd'];

		gator::writeLog($action);

		return;
	}


	/**
	 *
	 * Paste (copy) files from buffer to current directory
	 */
	public function pasteFromBuffer(){

		if (!isset($_SESSION['buffer']) || !isset($_SESSION['buffer']['files'])) return false;

		if ($_SESSION['buffer']['type'] == 'copy'){

			$this->copyFiles($_SESSION['buffer']['files'], $_SESSION['buffer']['directory'], $_SESSION['cwd']);

		}elseif($_SESSION['buffer']['type'] == 'cut'){

			$this->moveFiles($_SESSION['buffer']['files'], $_SESSION['buffer']['directory'], $_SESSION['cwd']);

		}else{
			return false;
		}

		return true;
	}


	/**
	 *
	 * Remove directory and all sub-content
	 */
	public function recursiveRemoveDirectory($directory, $empty=FALSE)
	{
		if(substr($directory,-1) == DS)
		{
			$directory = substr($directory,0,-1);
		}
		if(!file_exists($directory) || !is_dir($directory))
		{
			return FALSE;
		}elseif(is_readable($directory))
		{
			$handle = opendir($directory);
			while (FALSE !== ($item = readdir($handle)))
			{
				if($item != '.' && $item != '..')
				{
					$path = $directory.DS.$item;
					if(is_dir($path))
					{
						$this->recursiveRemoveDirectory($path);
					}else{
						unlink($path);
					}
				}
			}
			closedir($handle);
			if($empty == FALSE)
			{
				if(!rmdir($directory))
				{
					return FALSE;
				}
			}
		}
		return TRUE;
	}


	/**
	 *
	 * upload files with ajax
	 */
	public function ajaxUpload(){

		if ($_SERVER['REQUEST_METHOD'] != 'POST') die;

		require_once gatorconf::get('base_path')."/include/blueimp/server/php/upload.class.php";

		$upload_handler = new UploadHandler();

		header('Pragma: no-cache');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-Disposition: inline; filename="files.json"');
		header('X-Content-Type-Options: nosniff');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
		header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

		$filename = '?';
		if (isset($_FILES['files']['name'])) {
			$filename = $this->filterInput(implode(" ",$_FILES['files']['name']));

			if (in_array($filename, gatorconf::get('restricted_files'))) die;
		}

		gator::writeLog('upload file '.$filename);

		$upload_handler->post();

		die;
	}


	/**
	 *
	 * authenticate user
	 */
	public function authenticate(){

		$errors = null;

		// destroy guests if disabled
		if(isset($_SESSION['simple_auth']['username']) && $_SESSION['simple_auth']['username'] == 'guest' && gatorconf::get('allow_guests') == false){
			session_destroy();
			session_start();
		}


		// recover password 1/2 - send email
		if (gatorconf::get('enable_password_recovery')
				&& isset($_GET['recover_password'])
				&& !empty($_GET['recover_password'])
				&& isset($_POST['emaildata'])
				&& !empty($_POST['emaildata'])){

			$email = filter_var(rawurldecode($_POST['emaildata']), FILTER_SANITIZE_EMAIL);
			$user = gator::getUser($email, 'email');
				
			if ($user && filter_var($email, FILTER_VALIDATE_EMAIL)){

				$generatedKey = 'otp-'.sha1(mt_rand(10000,99999).time());
				gator::updateUser($user['username'], array('akey' => $generatedKey));

				$url = gatorconf::get('base_url').'/?otp='.$generatedKey;

				$subject = gatorconf::get('account_email_subject');
				$body = gatorconf::get('account_email_text'). "\n\n" .$url;

				$this->sendEmail($email, $subject, $body);

			}
				
			// flush url
			header('Location: '.gatorconf::get('base_url'));
			die;
		}
		// recover password 1/2 - direct link enter
		if (gatorconf::get('enable_password_recovery')
				&& isset($_GET['otp'])
				&& !empty($_GET['otp'])){

			$otp = strtolower(preg_replace("/[^a-z0-9\-]+/i", "-", $_GET['otp']));
			$user = gator::getUser($otp, 'akey');
			
			if ($user){
				gator::updateUser($user['username'], array('akey' => ''));
				$_SESSION['directlinkenter'] = 'passwordrecovery';
				$this->loginUser($user);
			}

			sleep(2);
			
			// flush url
			header('Location: '.gatorconf::get('base_url'));
			die;
		}

		if(!isset($_SESSION['simple_auth']['username']) || isset($_GET["login"])) {

			session_destroy();
			session_start();

			if(isset($_POST["submit"])) {

				$user = gator::getUser($_POST['username']);

				if (isset($user['permissions']) && !strstr($user['permissions'], 'r')
						|| ($user['username'] == 'guest' && gatorconf::get('allow_guests') == false)){
					$errors = lang::get("Access Forbidden");
					gator::writeLog('auth bad - not activated');
				}

				if (!isset($_POST['username']) || !isset($_POST['password']) || $_POST['username'] == '' || $_POST['password'] == ''){
					$errors = lang::get("Enter username and password.");
					gator::writeLog('auth bad - blank fields');
				}

				if (isset($user['akey']) && $user['akey'] != '' && strpos($user['akey'], 'otp-') === false){
					$errors = lang::get("Please open your email and click on the link to proceed.");
					gator::writeLog('auth bad - not activated');
				}

				if(!$errors && $user['username'] == $_POST['username'] && gator::checkPassword($_POST['password'], $user['password'])){
					$this->loginUser($user);
				}

				if(!$errors){
					$errors = lang::get("Wrong username or password.");
					gator::writeLog('auth bad - wrong username or password');
					sleep(1);
				}

			}


			if (!isset($_GET["login"]) && gatorconf::get('allow_guests') == true){

				$user = gator::getUser('guest');
				
				if($user){
					$this->loginUser($user);
				}
				// reload
				header('Location: '.gatorconf::get('base_url'));
				die;


			}


			gator::display("header.php");
			gator::display("login.php", array('errors' => $errors));
			gator::display("footer.php");
			exit;
		}
	}

	public function loginUser($user){
		
		// load full user's data to session
		foreach ($user as $key => $value){
			$_SESSION['simple_auth'][$key] = $value;
		}
		
		$_SESSION['simple_auth']['cryptsalt'] = gatorconf::get('encryption_salt');

		gator::writeLog('auth ok');

		// reload
		header('Location: '.gatorconf::get('base_url').'/?cd=');
		die;
	}

	/**
	 *
	 * Check users permissions
	 */
	public static function checkPermissions($mode){

		if (gatorconf::get('use_auth') != true) return true;

		for($i=0; $i<strlen($mode); $i++) {

			if (strstr($_SESSION['simple_auth']['permissions'], substr($mode, $i, 1)) == false){
				return false;
			}
		}

		return true;
	}


	/**
	 *
	 * get dir listing
	 */
	public function getDirectoryList()
	{
		$directory = $_SESSION['cwd'];
		$parent_directory = str_replace('\\', '/', dirname($directory));

		// check if home dir
		if ($directory == gatorconf::get('repository')) $home = true;

		// create an array to hold directory list
		$dirs = $files = array();

		// open dir and create a handler
		$handler = opendir($directory);

		// if cannot open, reset current dir to home
		if ($handler == false){
			$directory = $_SESSION['cwd'] = gatorconf::get('repository');
			$handler = opendir($directory);
		}

		// open directory and walk through the filenames
		while (false !== ($file = readdir($handler))) {

			// if file isn't this directory or its parent or resticted, add it to the results
			if ($file != "." && $file != ".." && !in_array($file, gatorconf::get('restricted_files'))) {

				if (filetype($directory.DS.$file) == 'dir'){

					$link = str_replace(gatorconf::get('repository'), '', $directory).DS.$file;
					$link = ltrim($link, '/\\');

					$dirs[] = array(
							'name' => $file,
							'crypt' => $this->encrypt($file),
							'link' => $this->encrypt($link),
							'size' => 0,
							'type' => 'dir',
							'time' => filemtime ($directory.DS.$file),
							'buffer' => $this->isBuffered($directory.DS.$file),
					);

				}else{

					if (gatorconf::get('direct_links_baseurl')) {
						$link = gatorconf::get('direct_links_baseurl').str_replace(gatorconf::get('direct_links_basedir'), '', $directory).DS.$file;
					}else{
						$link = gatorconf::get('base_url').str_replace(gatorconf::get('base_path'), '', $directory).DS.$file;
					}
			
					$files[] = array(
							'name' => $file,
							'crypt' => $this->encrypt($file),
							'size' => $this->formatBytes(filesize($directory.DS.$file)),
							'sizeb' => filesize($directory.DS.$file),
							'type' => $this->getFileType($file),
							'time' => filemtime ($directory.DS.$file),
							// create url from path
							'link' => $link,
							'buffer' => $this->isBuffered($directory.DS.$file),
					);
				}
			}
		}

		// tidy up: close the handler
		closedir($handler);

		$dirs = $this->sortByKey($dirs, 'name', true);

		// add back link
		if (!isset($home)){

			$link = str_replace(gatorconf::get('repository'), '', $parent_directory);
			$link = ltrim($link, '/\\');

			array_unshift($dirs, array(
			'name' => lang::get("Go Back"),
			'crypt' => $this->encrypt('..'),
			'link' => $this->encrypt($link),
			'size' => 0,
			'type' => 'back',
			'time' => 0,
			'buffer' => false,
			));
		}

		// build breadcrumbs
		$breadcrumb = array();
		$next = '';
		$bc = 'Home' . str_replace(gatorconf::get('repository'), '', $directory);
		$bc = explode('/', $bc);
		foreach ($bc as $bclink){
			if ($bclink != 'Home'){
				$next .= DS.$bclink;
				$next = ltrim($next, '/');
			}
			$breadcrumb[$bclink] = gatorconf::get('base_url').'/?cd='.$this->encrypt($next);
		}


		// sort files (not dirs)
		switch($_SESSION['sort']['by']){
			case 'date':
				$files = $this->sortByKey($files, 'time');
				break;

			case 'size':
				$files = $this->sortByKey($files, 'sizeb');
				break;

			case 'name':
			default:
				$files = $this->sortByKey($files, 'name', true);
				break;
		}

		return array('dirs' => $dirs, 'files' => $files, 'breadcrumb' => $breadcrumb);
	}


	/**
	 *
	 * Sort array by key
	 */
	public function sortByKey($array, $key, $natural = false) {

		$order = $_SESSION['sort']['order'];

		$result = array();

		$values = array();
		foreach ($array as $id => $value) {
			$values[$id] = isset($value[$key]) ? $value[$key] : '';
		}
			
		if ($natural){
			natcasesort($values);
			if ($order != 1) $values = array_reverse($values, true);
		}else{
			if ($order == 1)
				asort($values);
			else
				arsort($values);
		}

		foreach ($values as $key => $value) {
			$result[$key] = $array[$key];
		}

		return $result;
	}


	/**
	 *
	 * Return file type based on extension
	 */
	public static function getFileType($filename){

		if (preg_match("/^.*\.(jpg|jpeg|png|gif|bmp)$/i", $filename) != 0){

			return 'image';

		}elseif (preg_match("/^.*\.(zip)$/i", $filename) != 0){

			return 'zip';
		}

		return 'generic';
	}


	/**
	 *
	 * Bytes formatter
	 */
	public function formatBytes($bytes, $precision = 0) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . ' ' . $units[$pow];
	}


	/**
	 *
	 * Helper for reading php.ini settings
	 */
	public static function returnBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}


	/**
	 *
	 * Display fatal errors
	 */
	public static function error($message){

		echo "</script><script>document.write('')</script>";
		echo "<div class=\"warning\"><strong>{$message}</strong></div><a href=\"?logout=1\">".lang::get("Sign in")."</a>";

		gator::writeLog('error - '.$message);

		die;
	}


	/**
	 *
	 * do some config validation
	 */
	public static function validateConf($config, $param){

		// do some extra work on repository config query
		if ($param == 'repository'){

			// if user is logged in and has a homedir - set it to be repository
			if( isset($_SESSION['simple_auth']['username'])) {

				$user = gator::getUser($_SESSION['simple_auth']['username']);

				if ($user['username'] == $_SESSION['simple_auth']['username'] && isset($user['homedir']) && $user['homedir'] != false){
					$config['repository'] = $user['homedir'];
				}
			}

			// error if repository does not exist
			if (!is_dir($config['repository'])){
				gator::error('Directory does not exist: '.$config['repository']);
			}
		}


		// max_filesize check server's conflict
		if ($param == 'max_filesize'){

			$php_post_max_size =  gator::returnBytes(ini_get('post_max_size'));
			$php_upload_max_filesize =  gator::returnBytes(ini_get('upload_max_filesize'));

			if ($config['max_filesize'] > $php_post_max_size || $config['max_filesize'] > $php_upload_max_filesize){
				gator::error('Config param max_filesize is bigger than php server setting: post_max_size = '.$php_post_max_size.', upload_max_filesize = '.$php_upload_max_filesize);

			}
		}


		// convert array to regexp
		if ($config['accept_file_extensions'] == '*' || in_array('*', $config['accept_file_extensions'])){
			$config['accept_file_extensions'] = '/\.+/';
		}else{
			$config['accept_file_extensions'] = '/(\.|\/)('.implode('|', $config['accept_file_extensions']).')$/i';
		}

		// dependencies
		if ($config['use_lightbox_gallery']) $config['allow_file_links'] = true;
		if (!$config['use_auth']) $config['encrypt_url_actions'] = false;

		// advanced: encryption salt
		$config['encryption_salt'] = $_SERVER['SERVER_NAME'];

		// advanced: mode when creating new directory (ignored on windows)
		$config['new_dir_mode'] = 0755;

		// strip trailing slash & forward slashes for fs
		$config['repository'] = rtrim($config['repository'], "/\\");
		$config['repository'] = str_replace('\\', '/', $config['repository']);
		$config['base_url'] = rtrim($config['base_url'], "/\\");

		return $config;
	}


	/**
	 *
	 * encrypt string
	 */
	public function encrypt($string)
	{
		// test if encryption is off or blank string
		if (gatorconf::get('encrypt_url_actions') != true || !isset($_SESSION['simple_auth']['cryptsalt']) || $string == '') return $string;
			
		$key = $_SESSION['simple_auth']['cryptsalt'];
			
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));

		// url safe
		$ret = strtr($encrypted, '+/=', '-_~');

		return $ret;
	}

	/**
	 *
	 * decrypt
	 */
	public function decrypt($string)
	{
		// test if encryption is off or blank string
		if (gatorconf::get('encrypt_url_actions') != true || !isset($_SESSION['simple_auth']['cryptsalt']) || $string == '') return $string;
			
		$key = $_SESSION['simple_auth']['cryptsalt'];
			
		// clean url safe
		$encrypted = strtr($string, '-_~', '+/=');
			
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

		return $decrypted;
	}

	/**
	 *
	 * hash user's password
	 */
	public static function hashPassword($plainPassword)
	{
		// use md5
		//return md5($plainPassword);

		// use openwall.com phpass class
		$hasher = new PasswordHash(8, true);
		return $hasher->HashPassword($plainPassword);

	}

	/**
	 *
	 * test user's password
	 */
	public static function checkPassword($plainPassword, $hashedPassword)
	{
		// try with md5
		if (md5($plainPassword) == $hashedPassword) return true;

		// using openwall.com phpass class
		$hasher = new PasswordHash(8, true);

		return $hasher->CheckPassword( $plainPassword, $hashedPassword );
	}



	/**
	 *
	 * create directory tree html
	 */
	public function getDirectoryTree($path, $skip_files = false, $link_prefix = '') {

		$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

		$dom = new DomDocument("1.0");

		$li = $dom;
		$ul = $dom->createElement('ul');
		$li->appendChild($ul);
		$el = $dom->createElement('li', 'Home');
		$at = $dom->createAttribute('clink');
		$at->value = $link_prefix;
		$el->appendChild($at);
		$ul->appendChild($el);

		$node = $ul;
		$depth = -1;


		foreach($objects as $object){

			$name = $object->getFilename();

			// skip unwanted files
			if ($name == '.' || $name == '..' || in_array($name, gatorconf::get('restricted_files'))) continue;

			$path = str_replace('\\', '/', $object->getPathname());
			$isDir = is_dir($path);

			$link = str_replace(gatorconf::get('repository'), '', $path);
			$link = gator::encodeurl(ltrim($link, '/\\'));

			$skip = false;


			if (($isDir == false && $skip_files == true )){
				// skip unwanted files
				$skip = true;
			}elseif($isDir == false){
				// this is regural file, no links here
				$link = '';
			}else{
				// this is dir
				$link = $link;
			}

			if ($objects->getDepth() == $depth){
				//the depth hasnt changed so just add another li
				if (!$skip){
					$el = $dom->createElement('li', $name);
					$at = $dom->createAttribute('clink');
					$at->value = $link_prefix.$this->encrypt($link);
					$el->appendChild($at);
					if (!$isDir) $el->appendChild($dom->createAttribute('isfile'));

					$node->appendChild($el);

				}
			}
			elseif ($objects->getDepth() > $depth){
				//the depth increased, the last li is a non-empty folder
				$li = $node->lastChild;
				$ul = $dom->createElement('ul');
				$li->appendChild($ul);
				if (!$skip){
					$el = $dom->createElement('li', $name);
					$at = $dom->createAttribute('clink');
					$at->value = $link_prefix.$this->encrypt($link);
					$el->appendChild($at);
					if (!$isDir) $el->appendChild($dom->createAttribute('isfile'));

					$ul->appendChild($el);
				}
				$node = $ul;
			}
			else{
				//the depth decreased, going up $difference directories
				$difference = $depth - $objects->getDepth();
				for ($i = 0; $i < $difference; $difference--){
					$node = $node->parentNode->parentNode;
				}
				if (!$skip){
					$el = $dom->createElement('li', $name);
					$at = $dom->createAttribute('clink');
					$at->value = $link_prefix.$this->encrypt($link);
					$el->appendChild($at);
					if (!$isDir) $el->appendChild($dom->createAttribute('isfile'));

					$node->appendChild($el);
				}
			}
			$depth = $objects->getDepth();
		}

		return $dom->saveHtml();
	}


	/**
	 *
	 * write usage to log
	 */
	public static function writeLog($action){

		if (gatorconf::get('write_log') != true) return;

		if (isset($_SESSION['simple_auth']['username'])){

			$user = $_SESSION['simple_auth']['username'];

		}else{
			$user = 'unknown';
		}

		$ip = $_SERVER["REMOTE_ADDR"];

		$log = date('Y-m-d H:i:s')." | IP $ip | $user | $action \n";

		file_put_contents(gatorconf::get('base_path').DS.'usage.log', $log, FILE_APPEND);
	}


	/**
	 *
	 * send email
	 */
	public function sendEmail($to, $subject = '', $body = ''){

		$to = filter_var($to, FILTER_SANITIZE_EMAIL);
		if(!filter_var($to, FILTER_VALIDATE_EMAIL)) return false;

		// add email signature here
		$body .= gatorconf::get('mail_signature');

		// use simple php mail instead of smtp
		if (gatorconf::get('use_smtp_protocol') !== true){

			$from = gatorconf::get('mail_from_name').' <'.gatorconf::get('mail_from').'>';
			$headers = '';
			$headers .= "From: $from\n";
			$headers .= "Return-Path: $from\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

			mail($to, $subject, $body, $headers);
			gator::writeLog('email link sent to: '.$to);
			return;
		}


		require_once gatorconf::get('base_path')."/include/phpmailer/class.phpmailer.php";

		$mail = new phpmailer(true);

		try {
			$mail->SMTPDebug = gatorconf::get('mail_smtp_debug');
			$mail->SMTPSecure = gatorconf::get('mail_smtp_connection_security');
			$mail->IsSMTP(); // send via SMTP
			$mail->Host = gatorconf::get('mail_smtp_host'); // SMTP servers
			$mail->SMTPAuth = true; // turn on SMTP authentication
			$mail->Username = gatorconf::get('mail_smtp_username'); // SMTP username
			$mail->Password = gatorconf::get('mail_smtp_password'); // SMTP password
			$mail->From     = gatorconf::get('mail_from');
			$mail->FromName = gatorconf::get('mail_from_name');
			$mail->AddAddress($to);
			$mail->Subject  = $subject;
			$mail->Body = $body;
			$mail->Send();

			gator::writeLog('email link sent to: '.$to);

		} catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
			if (gatorconf::get('mail_smtp_debug')) die;
		} catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
			if (gatorconf::get('mail_smtp_debug')) die;
		}

		return;

	}


	/**
	 *
	 * shorten url via goo.gl service
	 */
	public function shortUrl($url){

		$postData = array('longUrl' => $url, 'key' => gatorconf::get('googl_shorturl_api_key'));
		$jsonData = json_encode($postData);
		$curlObj = curl_init();
		curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curlObj, CURLOPT_POST, 1);
		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
		$response = curl_exec($curlObj);
		//change the response json string to object
		$json = json_decode($response);
		curl_close($curlObj);
			
		$short_url = $json->id;
			
		return $short_url;
	}


	/**
	 *
	 * get all users
	 */
	static function getAllUsers(){

		//
		// use database
		//
		if(gatorconf::get('use_database')){

			$db = new DBDriver();
			$rs = $db->query("SELECT * FROM users");

			return $db->fetchAll($rs);
		}

		//
		// use user_config_file
		//
		$config_file = gatorconf::get('user_config_file');


		// load users database on start and on file change
		if (!isset($_SESSION['cached_users']) || $_SESSION['user_config_stamp'] != filemtime($config_file)){
			$_SESSION['user_config_stamp'] = filemtime($config_file);
			$_SESSION['cached_users'] = json_decode(file_get_contents($config_file), true);
		}

		// refresh user's permissions
		if (isset($_SESSION['simple_auth']['username']) && $_SESSION['simple_auth']['username'] != 'admin' && isset($_SESSION['simple_auth']['permissions'])){
			foreach ($_SESSION['cached_users'] as $key => $user) {
				// account match
				if ($user['username'] == $_SESSION['simple_auth']['username']){
					$_SESSION['simple_auth']['permissions'] = $user['permissions'];
					break;
				}
			}
		}

		if (!is_array($_SESSION['cached_users'])){
			gator::error('cannot read users file!');
		}

		return $_SESSION['cached_users'];

	}


	/**
	 *
	 * get single user
	 */
	static function getUser($value, $field = 'username'){

		//
		// use database
		//
		if(gatorconf::get('use_database')){

			$db = new DBDriver();

			$value = $db->escape($value);
			$rs = $db->query("SELECT * FROM users WHERE {$field} = '{$value}'");

			return $db->fetch($rs);
		}

		//
		// use user_config_file
		//
		$current_users = gator::getAllUsers();

		// search users
		foreach ($current_users as $user) {
			// account match
			if (isset($user[$field]) && $user[$field] == $value){
				return $user;
			}
		}

		return false;

	}


	/**
	 *
	 * update user
	 */
	static function updateUser($username, $data){

		//
		// use database
		//
		if(gatorconf::get('use_database')){

			$db = new DBDriver();

			$username = $db->escape($username);

			$fields = "";

			if (isset($data['homedir'])){
				$homedir = $db->escape($data['homedir']);
				$fields .= "homedir = '{$homedir}',";

				// if dir does not exist - try to create one
				if ($homedir != '' && !is_dir($homedir)){
					@mkdir($homedir, gatorconf::get('new_dir_mode'), true);
				}
			}

			if (isset($data['password']) && $data['password'] != ''){
				$password = gator::hashPassword($data['password']);
				$fields .= "password = '{$password}',";
			}

			if (isset($data['permissions'])){
				$permissions = $db->escape($data['permissions']);
				$fields .= "permissions = '{$permissions}',";
			}

			if (isset($data['akey'])){
				$activationkey = $db->escape($data['akey']);
				$fields .= "akey = '{$activationkey}',";
			}
			
			if (isset($data['email'])){
				$email = $db->escape($data['email']);
				$fields .= "email = '{$email}',";
			}

			// nothing to update, return
			if ($fields == "") return;

			$fields = rtrim($fields, ",");

			$sql = "
			UPDATE users SET
			{$fields}
			WHERE username = '{$username}'
			";

			return $db->execute($sql);
		}

		//
		// use user_config_file
		//
		$current_users = gator::getAllUsers();

		// update user
		foreach ($current_users as &$user) {
			// account match
			if ($user['username'] == $username){

				if (isset($data['password']) && $data['password'] != '') $user['password'] = gator::hashPassword($data['password']);
				if (isset($data['permissions'])) $user['permissions'] = $data['permissions'];
				if (isset($data['akey'])) $user['akey'] = $data['akey'];
				if (isset($data['email'])) $user['email'] = $data['email'];
				if (isset($data['homedir'])){
					$user['homedir'] = $data['homedir'];
					// if dir does not exist - try to create one
					if ($user['homedir'] != '' && !is_dir($user['homedir'])){
						@mkdir($user['homedir'], gatorconf::get('new_dir_mode'), true);
					}
				}
				break;
			}
		}

		$json_config = json_encode($current_users);
		file_put_contents(gatorconf::get('user_config_file'), $json_config, LOCK_EX);

		// cache again
		gator::getAllUsers();

		return;

	}


	/**
	 *
	 * add user
	 */
	static function addUser($username, $data){

		//
		// use database
		//
		if(gatorconf::get('use_database')){

			// drop if user already exists
			if (gator::getUser($username)) return;

			$db = new DBDriver();

			$username = $db->escape($username);
			$password = gator::hashPassword($data['password']);
			$permissions = $db->escape($data['permissions']);
			$homedir = $data['homedir'];
			$homedirdb = $db->escape($data['homedir']);
			$email = $db->escape($data['email']);
			$activationkey = $db->escape($data['akey']);

			$sql = "
			INSERT INTO users (username, password, permissions, homedir, email, akey)
			VALUES ('{$username}', '{$password}', '{$permissions}', '{$homedirdb}', '{$email}', '{$activationkey}')
			";

			// if dir does not exist - try to create one
			if ($homedir != '' && !is_dir($homedir)){
				@mkdir($homedir, gatorconf::get('new_dir_mode'), true);
			}

			return $db->execute($sql);
		}

		//
		// use user_config_file
		//
		$current_users = gator::getAllUsers();

		// drop if user already exists
		foreach ($current_users as &$user) {
			if ($user['username'] == $username) return;
		}

		// if dir does not exist - try to create one
		$homedir = $data['homedir'];
		if ($homedir != '' && !is_dir($homedir)){
			@mkdir($homedir, gatorconf::get('new_dir_mode'), true);
		}

		array_push($current_users, array(
		'username' => $username,
		'password' => gator::hashPassword($data['password']),
		'permissions' => $data['permissions'],
		'homedir' => $data['homedir'],
		'email' => $data['email'],
		'akey' => $data['akey']));

		$json_config = json_encode($current_users);
		file_put_contents(gatorconf::get('user_config_file'), $json_config, LOCK_EX);

		return;

	}



	/**
	 *
	 * delete user
	 */
	static function deleteUser($username){

		//
		// use database
		//
		if(gatorconf::get('use_database')){

			$db = new DBDriver();

			$username = $db->escape($username);

			$sql = "DELETE FROM users WHERE username = '{$username}'";

			return $db->execute($sql);
		}

		//
		// use user_config_file
		//
		$current_users = gator::getAllUsers();

		foreach ($current_users as $key => $user) {

			if ($user['username'] == $username){

				unset($current_users[$key]);

				$json_config = json_encode($current_users);
				file_put_contents(gatorconf::get('user_config_file'), $json_config, LOCK_EX);

				break;
			}
		}

		return;
	}


}

class lang {
	public static function get($string) {

		$lang_conf = gatorconf::get('language');
		$lang_file = gatorconf::get('base_path')."/languages/{$lang_conf}.php";

		// caching: load language file on session start and on file change
		if (!isset($_SESSION['cached_lang']) || $_SESSION['lang_file_stamp'] != filemtime($lang_file)){
			require $lang_file;
			$_SESSION['lang_file_stamp'] = filemtime($lang_file);
			$_SESSION['cached_lang'] = $lang;
		}

		if ($_SESSION['cached_lang'][$string] == '') return $string;
		return $_SESSION['cached_lang'][$string];
	}
}
