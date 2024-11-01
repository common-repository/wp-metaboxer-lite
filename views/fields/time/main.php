<?php if (!defined ('ABSPATH')) die ('Access not allowed.');  ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>-hour"><?php esc_attr_e($label); ?></label>
	<select id="<?php esc_attr_e($name); ?>-hour" class="mtbxr-time-hour" name="<?php esc_attr_e($name); ?>[hour]">
		<option value=""></option>
		<?php foreach($hours as $key=>$hour): ?>
		<option value="<?php esc_attr_e($key); ?>" <?php echo ($time['hour']==$key) ? 'selected="selected"' : ''; ?>><?php printf("%02d", $hour); ?></option>
		<?php endforeach; ?>
	</select> : 
	<input type="text" class="mtbxr-time-minute" name="<?php esc_attr_e($name); ?>[minute]" maxlength="2" value="<?php esc_attr_e($time['minute']); ?>" /> : 
	<input type="text" class="mtbxr-time-second" name="<?php esc_attr_e($name); ?>[second]" maxlength="2" value="<?php esc_attr_e($time['second']); ?>" /> 
	<select class="mtbxr-time-meridiem" name="<?php esc_attr_e($name); ?>[meridiem]">
		<option value=""></option>
		<option value="am" <?php echo ($time['meridiem']=='am') ? 'selected="selected"' : ''; ?>>AM</option>
		<option value="pm" <?php echo ($time['meridiem']=='pm') ? 'selected="selected"' : ''; ?>>PM</option>
	</select> <br />
	<span class="note"><?php esc_attr_e($note); ?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>