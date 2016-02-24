


<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Singapore');
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../xls_classes/PHPExcel/IOFactory.php';

//read in template
$objPHPExcel = PHPExcel_IOFactory::load("SCELSE_Sequencing_SampleSubmissionForm2.xlsx");

//insert new rows 
$num_of_rows_need = '5';
$objWorksheet = $objPHPExcel->getActiveSheet();
for($i = 1; $i <= 5; $i++){
	$objWorksheet->insertNewRowBefore(15);
}

$test_variable = 'testinnng';
$starting_row = 15;

//write to file
$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A15', $test_variable)
                            ->setCellValue('A16', "Name")
                            ->setCellValue('A17', "Email")
                            ->setCellValue('A18', "Phone")
                            ->setCellValue('A19', "Address");
// Save Excel 2007 file							
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$file_name = 'test.xls';
$path = $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/airmicrobiomes/';
$file_loc = $path.'/browse_files/repository/shared/sequencing_sample_submission_forms/';
$objWriter->save($file_loc.$file_name);
// Echo done
echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
echo date('H:i:s') , " Done reading file" , EOL;
echo 'File has been created in ' , getcwd() , EOL;
//echo '<a href='.$file_loc.$file_name.' download>Click here</a>';
echo '<a href=/series/dynamic/airmicrobiomes/browse_files/repository/shared/sequencing_sample_submission_forms/'.$file_name.' download>Click here</a>';
