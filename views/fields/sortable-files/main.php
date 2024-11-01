<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<div class="mtbxr-sortable-files">
		<?php foreach($files as $file): ?>
				<div class="mtbxr-sortable-file">
					<p><?php echo esc_url($file[1]); ?></p>
					<input type="hidden" value="<?php esc_attr_e($file[0]); ?>" name="<?php esc_attr_e($name); ?>[]" />
					<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-file-drag"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
					<span class="mtbxr-file-action">
						<a class="mtbxr-file-edit" href="#"><?php _e('Edit', 'mtbxr'); ?></a>
						<a class="mtbxr-file-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
					</span>
				</div>
				<?php
			endforeach;
		?>
	</div>
	<div class="clear"></div>
	<input class="mtbxr-add-file button-secondary" data-post-id="<?php esc_attr_e($post_id); ?>" type="button" value="<?php _e('Add File', 'mtbxr'); ?>" />
	<br />
	<span class="note"><?php esc_attr_e($note);?></span>
	<div class="mtbxr-sortable-file-skeleton">
		<div class="mtbxr-sortable-file">
			<p></p>
			<input type="hidden" value="" name="<?php esc_attr_e($name); ?>_dummy[]" />
			<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-file-drag"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
			<span class="mtbxr-file-action">
				<a class="mtbxr-file-edit" href="#"><?php _e('Edit', 'mtbxr'); ?></a>
				<a class="mtbxr-file-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
			</span>
		</div>
	</div>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>