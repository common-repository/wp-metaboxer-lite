<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<div class="mtbxr-sortable-textareas">
		<?php foreach($textareas as $value): ?>
				<div class="mtbxr-sortable-textarea">
					<textarea name="<?php esc_attr_e($name); ?>[]"><?php echo esc_textarea($value); ?></textarea>
					<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-textarea-drag"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
					<span class="mtbxr-textarea-action">
						<a class="mtbxr-textarea-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
					</span>
				</div>
				<?php
			endforeach;
		?>
	</div>
	<div class="clear"></div>
	<input class="mtbxr-textarea-add button-secondary" data-post-id="<?php esc_attr_e($post_id); ?>" type="button" value="<?php _e('Add Textarea', 'mtbxr'); ?>" />
	<br />
	<span class="note"><?php esc_attr_e($note);?></span>
	<div class="mtbxr-sortable-textarea-skeleton">
		<div class="mtbxr-sortable-textarea">
			<textarea name="<?php esc_attr_e($name); ?>_dummy[]"></textarea>
			<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-textarea-drag"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
			<span class="mtbxr-textarea-action">
				<a class="mtbxr-textarea-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
			</span>
		</div>
	</div>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>