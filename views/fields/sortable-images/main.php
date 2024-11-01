<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<div class="mtbxr-sortable-images">
		<?php foreach($images as $image): ?>
				<div class="mtbxr-sortable-image">
					<img width="100" src="<?php echo esc_url($image[1]); ?>" alt="" />
					<input type="hidden" value="<?php esc_attr_e($image[0]); ?>" name="<?php esc_attr_e($name); ?>[]" />
					<span class="mtbxr-image-action">
						<a class="mtbxr-image-edit" data-post-id="<?php esc_attr_e($post_id); ?>" href="#">Edit</a>
						<a class="mtbxr-image-delete" href="#">Delete</a>
					</span>
				</div>
				<?php
			endforeach;
		?>
	</div>
	<div class="clear"></div>
	<input class="mtbxr-add-image button-secondary" data-post-id="<?php esc_attr_e($post_id); ?>" id="<?php esc_attr_e($name); ?>" type="button" value="<?php _e('Add Image', 'mtbxr'); ?>" />
	<br />
	<span class="note"><?php esc_attr_e($note);?></span>
	<div class="mtbxr-sortable-image-skeleton">
		<div class="mtbxr-sortable-image">
			<img width="100" src="" alt="" />
			<input type="hidden" value="" name="<?php esc_attr_e($name); ?>_dummy[]" />
			<span class="mtbxr-image-action">
				<a class="mtbxr-image-edit" data-post-id="<?php esc_attr_e($post_id); ?>" href="#">Edit</a>
				<a class="mtbxr-image-delete" href="#">Delete</a>
			</span>
		</div>
	</div>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>