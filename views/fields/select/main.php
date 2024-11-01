<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<select <?php echo ($multiple=='true') ? 'multiple="multiple"' : ''; ?> id="<?php esc_attr_e($name); ?>" name="<?php esc_attr_e($name); ?>[]">
		<?php foreach($options as $option) : ?>
		<option value="<?php esc_attr_e($option['value']); ?>" <?php echo $option['selected']; ?> ><?php esc_attr_e($option['text']); ?></option>
		<?php endforeach; ?>
	</select>
	<span class="note"><?php esc_attr_e($note); ?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>