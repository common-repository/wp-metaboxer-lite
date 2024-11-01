<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for=""><?php esc_attr_e($label); ?></label>
	<?php foreach($options as $checkbox) : ?>
		<label class="mtbxr-radio" for="<?php esc_attr_e($name.'-'.$checkbox['value']); ?>">
			<input type="checkbox" name="<?php esc_attr_e($name); ?>[]" value="<?php esc_attr_e($checkbox['value']); ?>" id="<?php esc_attr_e($name.'-'.$checkbox['value']); ?>" <?php echo $checkbox['checked']; ?> />
			<?php esc_attr_e($checkbox['text']); ?>
		</label><br />
	<?php endforeach; ?>
	<span class="note"><?php esc_attr_e($note);?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>