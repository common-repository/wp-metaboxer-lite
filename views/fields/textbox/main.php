<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label> 
	<input class="mtbxr-textbox" type="text" name="<?php esc_attr_e($name); ?>" id="<?php esc_attr_e($name); ?>" value="<?php esc_attr_e( $value ); ?>" />
	<span class="note"><?php esc_attr_e($note);?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>