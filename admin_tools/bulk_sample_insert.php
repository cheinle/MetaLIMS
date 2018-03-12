<?php
if(!isset($_SESSION)) { session_start(); }
$path = $_SESSION['include_path']; //same as $path
include ($path.'/functions/admin_check.php');
include('../database_connection.php');
include('../index.php');
include('process_bulk_sample_insert.php');


$upload_dir = $path.'admin_tools/uploads'; 


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Upload Bulk Sample Insert Form</title>
		<style>
			.fileUpload {
			    position: relative;
			    overflow: hidden;
			    margin: 10px;
			}
			.fileUpload input.upload {
			    position: absolute;
			    top: 0;
			    right: 0;
			    margin: 0;
			    padding: 0;
			    font-size: 20px;
			    cursor: pointer;
			    opacity: 0;
			    filter: alpha(opacity=0);
			}

			button{
				border-radius: 5px;
			}
		</style>
</head>
<body>
		<div class="page-header">
		<h3>Upload Bulk Sample Insert Excel Form</h3>
		<?php
			$submitted = 'false';
			$msg = '';
		?>
		</div>

		<div class="page-header">
			<strong>Important Instructions & Notes:</strong><br>
			(BETA)
			<i>This function is used to bulk insert samples using the existing excel template <a href="bulk_sample_insert_template.xls"> download here</a> Form copies format of Sample Insert Form's Collection Info tab. Function is still in testing phase and built as a request from user. Tested on Window's machine<br></i>
			<strong>Warning: </strong>&nbsp All Projects, Locations, Relative Locations, Media Types, Sample Types, Storage Locations, & Samplers must already exist in MetaLIMS (please manually enter if needed). Sample date is taken from first sampler date given. User created fields must be named exactly the same as in database. Reminder: Please do not use '+' sign or ';' in user created field names<br>
		</div>

		<!--Paragraph for error message-->
		<div id="error-msg"></div>

		<form class = "registration" name="myForm" id="myForm" action="bulk_sample_insert.php" method="post" enctype="multipart/form-data">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<fieldset>
				<legend><strong>Attach Bulk Sample Insert Excel From (.xls)</strong></legend>

				<div class="form-group">
					<div class="col-md-8">
						<input type="text" id="uploadFile" placeholder="Choose File" style="font-size:16px" disabled="disabled" />
					</div>
					<div class="col-md-1">
						<div class="fileUpload btn btn-primary" style="float:left;">
						    <span>Upload</span>
						    <input type="file" id="uploadBtn" class="upload" name='validationFile' />
						</div>
					</div>
				</div>

		<script type="text/javascript">
			document.getElementById("uploadBtn").onchange = function () {
			    document.getElementById("uploadFile").value = this.value;
			};
		</script>

		<?php
			if(isset($_POST['button']) && $_POST['button'] == 'Submit')
			{
				try
				{

					$submitted = 'true';
			
					// Undefined | Multiple Files | $_FILES Corruption Attack
					// If this request falls under any of them, treat it invalid
					if (!isset($_FILES['validationFile']['error']) || is_array($_FILES['validationFile']['error']))
					{
						throw new RuntimeException('Invalid parameters.');
					}

					// Check $_FILES['validationFile']['error'] value
					switch ($_FILES['validationFile']['error'])
					{
						case UPLOAD_ERR_OK:
							break;
						case UPLOAD_ERR_NO_FILE:
							throw new RuntimeException('No file sent.');
						case UPLOAD_ERR_INI_SIZE:
						case UPLOAD_ERR_FORM_SIZE:
							throw new RuntimeException('Exceeded filesize limit.');
						default:
							throw new RuntimeException('Unknown errors.');
					}

					// You should also check filesize here
					if ($_FILES['validationFile']['size'] > 5000000)
					{
						throw new RuntimeException('Exceeded filesize limit.');
					}

					// Check mime type
					// DO NOT TRUST $_FILES['validationFile']['mime'] VALUE !!
					// Check MIME Type by yourself
					$finfo = new finfo(FILEINFO_MIME_TYPE);
					//print $finfo->file($_FILES['validationFile']['tmp_name']);
					if (false === $ext = array_search($finfo->file($_FILES['validationFile']['tmp_name']), array('xls' => 'application/vnd.ms-excel'),true))
					{
						throw new RuntimeException('Invalid file format. File must be .csv or .txt');
					}

					//delete all files in the upload directory before uploading a new file
					$files = glob("$upload_dir/*"); // get all file names
					foreach($files as $file)
					{
						if(is_file($file))
						unlink($file); // delete file
					}

					// You should name it uniquely
					// DO NOT USE $_FILES['validationFile']['name'] WITHOUT ANY VALIDATION !!
					// On this example, obtain safe unique name from its binary data
					$encName = sha1_file($_FILES['validationFile']['tmp_name']);
					$file = $upload_dir."/".$encName.".$ext";		// passing file name to perl script

					$length = 5;
					$randomString = substr(str_shuffle(md5(time())),0,$length);

					if (!move_uploaded_file($_FILES['validationFile']['tmp_name'],sprintf("$upload_dir/%s.%s",$encName,$ext)))
					{
						echo $upload_dir;
						throw new RuntimeException('Failed to move uploaded file.'.$upload_dir);
					}
					else
					{
						$result = bulk_sample_insert_parse($ext,$file,$randomString,$path,$dbc);
						//echo $result;

						//if(is_numeric($result) && $result > 0){
						if($result == 'Fini!'){
							$msg = $msg."<span style='color:green;'>File uploaded and processed successfully. Please retrieve processed output file <a href='download.php?download_file=process_bulk_sample_insert_output.txt'>here</a><br>Please see <a href='download.php?download_file=process_bulk_sample_insert_error.txt'>error file</a> for any errors or removed lines</span><br /></a>";
						}
						else{
							$msg = $msg."<span style='color:red;'>*** File processing error. Please check your file again or you may contact the admin for help. Please see <a href='download.php?download_file=process_bulk_sample_insert_error.txt'>error file</a>***</span>";
						}
					}
				}
				catch (RuntimeException $e)
				{
					$error = $e->getMessage();
					$msg = $msg."<br> Error:  " . $error;
				}
			}
		?>


		<input type='submit' name='button' id='button' class = "button" value='Submit'/>
		</fieldset>
		</form>
			<script>
				$(document).ready(function(){
					var msg = <?=json_encode($msg)?>;
					$("#error-msg").addClass( "border" );
					$("#error-msg").append("<b>MESSAGE:"+msg+"</b>");
				});
			</script>
		</div>
	</div>
</body>
</html>
