<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<textarea class="mtbxr-textarea" name="<?php esc_attr_e($name); ?>" id="<?php esc_attr_e($name); ?>"><?php echo esc_textarea ( $value ); ?></textarea>
	<span class="note"><?php esc_attr_e($note);?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>