<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for=""><?php esc_attr_e($label); ?></label>
	<input type="hidden" name="<?php esc_attr_e($name); ?>" value="0" />
	<label class="mtbxr-radio" for="<?php esc_attr_e($name); ?>">
		<input type="checkbox" name="<?php esc_attr_e($name); ?>" value="1" id="<?php esc_attr_e($name); ?>" <?php echo ($value=='1') ? 'checked="checked"' : ''; ?> />
		<?php esc_attr_e($label); ?>
	</label><br />
	<span class="note"><?php esc_attr_e($note);?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>