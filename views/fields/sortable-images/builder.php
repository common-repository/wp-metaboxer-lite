<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-admin-box">
	<div class="mtbxr-admin-header">
		<span class="mtbxr-admin-title"><?php _e('Sortable Images', 'mtbxr'); ?></span>
		<span class="mtbxr-admin-actions">
			<a class="mtbxr-admin-toggle" href="#"><?php _e('Toggle', 'mtbxr'); ?></a>
			<a class="mtbxr-admin-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
		</span>
		<div class="clear"></div>
	</div>
	<div class="mtbxr-admin-body">
		<?php echo $debug; ?>
		
		<input type="hidden" name="mtbxr[fields][<?php echo $index; ?>][type]" value="sortable_images" class="update-my-index" />
		
		<label for="mtbxr-label-<?php echo $index; ?>" class="update-my-index"><?php _e('Label', 'mtbxr'); ?> <span class="req">*</span></label>
		<input id="mtbxr-label-<?php echo $index; ?>" class="update-my-index mtbxr-label" type="text" name="mtbxr[fields][<?php echo $index; ?>][label]" value="<?php echo $label; ?>" />
		<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />
		
		<label for="mtbxr-uid-<?php echo $index; ?>" class="update-my-index"><?php _e('Unique Key', 'mtbxr'); ?> <span class="req">*</span></label>
		<input id="mtbxr-uid-<?php echo $index; ?>" class="update-my-index mtbxr-uid" type="text" name="mtbxr[fields][<?php echo $index; ?>][uid]" value="<?php echo $uid; ?>" />
		<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> <br />
		
		<div class="mtbxr-admin-more">
			<a href="#"><?php _e('More Settings', 'mtbxr'); ?></a>
		</div>
		<div class="mtbxr-admin-more-content">
			<label for="mtbxr-note-<?php echo $index; ?>" class="update-my-index"><?php _e('Note', 'mtbxr'); ?></label>
			<input id="mtbxr-note-<?php echo $index; ?>" class="update-my-index" type="text" name="mtbxr[fields][<?php echo $index; ?>][note]" value="<?php echo $note; ?>" />
			<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a>
		</div>
	</div>
</div>