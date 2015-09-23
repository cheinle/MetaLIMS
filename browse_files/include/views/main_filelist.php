<table class="file-list responsive">
<?php $i = 1;?>

	<tbody>
	
	<?php if(!empty($params['dirs'])) foreach ($params['dirs'] as $file):?>
		<tr class="directory <?php if ($file['type'] == "back") echo 'back-button';?>">
		
		  <?php if (gator::checkPermissions('rw')):?>
			<?php if ($file['type'] != 'back'):?>
			<td class="chkboxes"><input type="checkbox" name="<?php echo $i++;?>" value="<?php echo $file['crypt']?>" /></td>
			<?php else:?>
			<td class="chkboxes"><a class="back" href="?cd=<?php echo $file['link']?>"></a></td>
			<?php endif;?>
		  <?php else:?>
		  	<?php if ($file['type'] != 'back'):?>
			<td class="chkboxes"></td>
			<?php else:?>
			<td class="chkboxes"><a class="back" href="?cd=<?php echo $file['link']?>"></a></td>
			<?php endif;?>
		  <?php endif;?>
		 
			<td class="filename" colspan=2>
			 <?php if ($file['name'] == ".."):?>
				<a href="?cd=<?php echo $file['link']?>"><?php echo lang::get("Go Back")?></a>
		     <?php else:?>
			 	<a <?php if($file['buffer']!=false) echo 'class="'.$file['buffer'].'"';?> href="?cd=<?php echo gator::encodeurl(($file['link']))?>"><?php echo $file['name']?></a>
			 <?php endif;?>
			</td>
			
			<td class="filename">
			 <?php if ($file['type'] != 'back') echo lang::get("Folder")?>
			</td>
			
			<td class="actions">
			 <?php if (gator::checkPermissions('w') && $file['type'] != 'back'):?>
			 <button type="button" class="action-info" data-type="<?php echo $file['type']?>" data-name="<?php echo $file['name']?>" data-crypt="<?php echo $file['crypt']?>" data-time="<?php echo date(gatorconf::get('time_format'), $file['time'])?>"></button>
			 <?php endif;?>
			 
			</td>

	
		</tr>
	<?php endforeach;?>
	
	<?php if(!empty($params['files'])) foreach ($params['files'] as $file):?>
	
		<tr class="file">
	
		  	<?php if (gator::checkPermissions('rw')):?>
			<td class="chkboxes"><input type="checkbox" name="<?php echo $i++;?>" value="<?php echo $file['crypt']?>" /></td>
			<?php else:?>
			<td class="chkboxes"></td>
		  	<?php endif;?>
			
			<?php if (gatorconf::get('allow_file_links')):?>
			 <td class="filename">
			  <a <?php if($file['buffer']!=false) echo 'class="'.$file['buffer'].'"';?> <?php if(gatorconf::get('use_lightbox_gallery') && $file['type'] == 'image') echo 'rel="lightbox[images]"';?> href="<?php echo gator::encodeurl($file['link'])?>" target="_blank"><?php echo $file['name']?></a>
			 </td>
			<?php else:?>
			 <td class="filename">
			  <a <?php if($file['buffer']!=false) echo 'class="'.$file['buffer'].'"';?>><?php echo $file['name']?></a>
			 </td>
			<?php endif;?>
			
			<td class="filesize"><?php echo $file['size']?></td>
			<td class="filetime"><?php echo date(gatorconf::get('time_format'), $file['time'])?></td>

			<?php if (gator::checkPermissions('r')):?>
			<td class="actions">
			 <button type="button" class="action-info" data-type="<?php echo $file['type']?>" data-link="<?php echo gator::encodeurl($file['link'])?>" data-name="<?php echo $file['name']?>" data-crypt="<?php echo $file['crypt']?>" data-size="<?php echo $file['size']?>" data-time="<?php echo date(gatorconf::get('time_format'), $file['time'])?>"></button>
			</td>
			<?php endif;?>
			
						
		</tr>
	<?php endforeach;?>
	
	<?php if (empty($params['files']) && count($params['dirs']) <= 1):?>
		<tr class="file">
			<td class="chkboxes"> </td>
			
			<td class="filename" colspan=3>
			 <?php echo lang::get("This folder is empty")?>
			</td>
			
			<td class="actions">
			</td>
	
		</tr>
	<?php endif;?>


	</tbody>
</table>