<?php if (!defined ('ABSPATH')) die ('Access not allowed.');  ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>-month"><?php esc_attr_e($label); ?></label>
	<select class="mtbxr-datetime-month" id="<?php esc_attr_e($name); ?>-month" name="<?php esc_attr_e($name); ?>[month]">
		<option value=""></option>
		<?php foreach($months as $key=>$month): ?>
		<option value="<?php esc_attr_e($key); ?>" <?php echo ($datetime['month']==$key) ? 'selected="selected"' : ''; ?>><?php esc_attr_e($month); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="text" class="mtbxr-datetime-day" name="<?php esc_attr_e($name); ?>[day]" maxlength="2" value="<?php esc_attr_e($datetime['day']); ?>" />
	<input type="text" class="mtbxr-datetime-year" name="<?php esc_attr_e($name); ?>[year]" maxlength="4" value="<?php esc_attr_e($datetime['year']); ?>" />
	@
	<select class="mtbxr-datetime-hour" name="<?php esc_attr_e($name); ?>[hour]">
		<option value=""></option>
		<?php foreach($hours as $key=>$hour): ?>
		<option value="<?php esc_attr_e($key); ?>" <?php echo ($datetime['hour']==$key) ? 'selected="selected"' : ''; ?>><?php printf("%02d", $hour); ?></option>
		<?php endforeach; ?>
	</select> : 
	<input type="text" class="mtbxr-datetime-minute" name="<?php esc_attr_e($name); ?>[minute]" maxlength="2" value="<?php esc_attr_e($datetime['minute']); ?>" /> : 
	<input type="text" class="mtbxr-datetime-second" name="<?php esc_attr_e($name); ?>[second]" maxlength="2" value="<?php esc_attr_e($datetime['second']); ?>" /> 
	<select class="mtbxr-datetime-meridiem" name="<?php esc_attr_e($name); ?>[meridiem]">
		<option value=""></option>
		<option value="am" <?php echo ($datetime['meridiem']=='am') ? 'selected="selected"' : ''; ?>>AM</option>
		<option value="pm" <?php echo ($datetime['meridiem']=='pm') ? 'selected="selected"' : ''; ?>>PM</option>
	</select> <br />
	<span class="note"><?php esc_attr_e($note); ?></span>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>