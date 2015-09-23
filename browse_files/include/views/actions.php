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
<div class="nice radius button split dropdown right sort-button">
  <a class="sort-invert"><?php echo lang::get("Sort by")?> <?php echo lang::get(ucfirst($_SESSION['sort']['by']))?></a>
  <span></span>
  <ul>
    <li><a class="sort-by-name"><?php echo lang::get("Sort by Name")?></a></li>
    <li><a class="sort-by-date"><?php echo lang::get("Sort by Date")?></a></li>
    <li><a class="sort-by-size"><?php echo lang::get("Sort by Size")?></a></li>
  </ul>
</div>

</div>