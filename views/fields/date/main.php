<?php if (!defined ('ABSPATH')) die ('Access not allowed.');  ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>-month"><?php esc_attr_e($label); ?></label>
	<select class="mtbxr-date-month" id="<?php esc_attr_e($name); ?>-month" name="<?php esc_attr_e($name); ?>[month]">
		<?php foreach($months as $key=>$month): ?>
		<option value="<?php esc_attr_e($key); ?>" <?php echo ($date['month']==$key) ? 'selected="selected"' : ''; ?>><?php esc_attr_e($month); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="text" class="mtbxr-date-day" name="<?php esc_attr_e($name); ?>[day]" maxlength="2" value="<?php esc_attr_e($date['day']); ?>" />
	<input type="text" class="mtbxr-date-year" name="<?php esc_attr_e($name); ?>[year]" maxlength="4" value="<?php esc_attr_e($date['year']); ?>" />
	<input type="hidden" value="<?php echo $saved_value; ?>" class="mtbxr-date-hidden" />
	<br />
	<span class="note"><?php esc_attr_e($note); ?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>