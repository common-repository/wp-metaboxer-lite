<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-admin-box" data-index="<?php echo $index; ?>">
	<div class="mtbxr-admin-header">
		<span class="mtbxr-admin-title"><?php _e('Select', 'mtbxr'); ?></span>
		<span class="mtbxr-admin-actions">
			<a class="mtbxr-admin-toggle" href="#"><?php _e('Toggle', 'mtbxr'); ?></a>
			<a class="mtbxr-admin-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
		</span>
		<div class="clear"></div>
	</div>
	
	<div class="mtbxr-admin-body">
		<?php echo $debug; ?>
		
		<input type="hidden" name="mtbxr[fields][<?php echo $index; ?>][type]" value="select" class="update-my-index" />
		
		<label for="mtbxr-label-<?php echo $index; ?>" class="update-my-index"><?php _e('Label', 'mtbxr'); ?> <span class="req">*</span></label>
		<input id="mtbxr-label-<?php echo $index; ?>" class="update-my-index mtbxr-label" type="text" name="mtbxr[fields][<?php echo $index; ?>][label]" value="<?php echo $label; ?>" />
		<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />
		
		<label for="mtbxr-uid-<?php echo $index; ?>" class="update-my-index"><?php _e('Unique Key', 'mtbxr'); ?> <span class="req">*</span></label>
		<input id="mtbxr-uid-<?php echo $index; ?>" class="update-my-index mtbxr-uid" type="text" name="mtbxr[fields][<?php echo $index; ?>][uid]" value="<?php echo $uid; ?>" />
		<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />

		<div class="sep"></div>
		
		<label for="" class="mtbxr-options-table"><?php _e('Options', 'mtbxr'); ?></label> 
		<table class="mtbxr-options-table">
			<tr>
				<th><?php _e('Value', 'mtbxr'); ?></th>
				<th><?php _e('Text', 'mtbxr'); ?></th>
				<th>&nbsp;</th>
			</tr>
			<?php foreach($options as $i=>$option) { ?>
			<tr class="row">
				<td><input class="mtbxr-option-value update-my-index" type="text" name="mtbxr[fields][<?php echo $index; ?>][options][<?php echo $i; ?>][value]" value="<?php echo $option['value']; ?>" /></td>
				<td><input class="mtbxr-option-text update-my-index" type="text" name="mtbxr[fields][<?php echo $index; ?>][options][<?php echo $i; ?>][text]" value="<?php echo $option['text']; ?>" /></td>
				<td><a href="#" class="mtbxr-option-delete"><?php _e('Delete', 'mtbxr'); ?></a></td>
			</tr>
			<?php } ?>
		</table>
		<a href="#" class="mtbxr-option-add"><?php _e('Add', 'mtbxr'); ?></a>
		<div class="clear"></div>
		
		<div class="mtbxr-admin-more">
			<a href="#"><?php _e('More Settings', 'mtbxr'); ?></a>
		</div>
		<div class="mtbxr-admin-more-content">
			<label for="mtbxr-multiple-<?php echo $index; ?>" class="update-my-index"><?php _e('Multiple', 'mtbxr'); ?></label>
			<select id="mtbxr-multiple-<?php echo $index; ?>" class="update-my-index" name="mtbxr[fields][<?php echo $index; ?>][multiple]">
				<option <?php echo ($multiple=='') ? 'selected="selected"' : ''; ?> value=""><?php _e('No', 'mtbxr'); ?></option>
				<option <?php echo ($multiple=='true') ? 'selected="selected"' : ''; ?> value="true"><?php _e('Yes', 'mtbxr'); ?></option>
			</select> <a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />
			
			<label for="mtbxr-default-<?php echo $index; ?>" class="update-my-index"><?php _e('Default', 'mtbxr'); ?></label>
			<input id="mtbxr-default-<?php echo $index; ?>" class="update-my-index" type="text" name="mtbxr[fields][<?php echo $index; ?>][default]" value="<?php echo $default; ?>" />
			<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />
			
			<label for="mtbxr-note-<?php echo $index; ?>" class="update-my-index"><?php _e('Note', 'mtbxr'); ?></label>
			<input id="mtbxr-note-<?php echo $index; ?>" class="update-my-index" type="text" name="mtbxr[fields][<?php echo $index; ?>][note]" value="<?php echo $note; ?>" />
			<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />
			
			<label for="mtbxr-data-source-<?php echo $index; ?>" class="update-my-index"><?php _e('Data Source', 'mtbxr'); ?></label>
			<input id="mtbxr-data-source-<?php echo $index; ?>" class="update-my-index" type="text" name="mtbxr[fields][<?php echo $index; ?>][data_source]" value="<?php echo $data_source; ?>" />
			<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a>
		</div>
	</div>
</div>