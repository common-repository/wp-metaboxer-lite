<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<div class="mtbxr-vimeo-videos">
		<?php foreach($textboxes as $i=>$value): ?>
				<div class="mtbxr-vimeo-video">
					
					<?php
					$push = '';
					if( isset($thumbnails[$i]) && !empty($thumbnails[$i]) ):
						$push = 'push';
					?>
						<a class="mtbxr-vimeo-preview" href="<?php esc_attr_e($value); ?>" target="_blank">
							<img src="<?php echo $thumbnails[$i]; ?>" alt="preview" />
						</a>
					<?php endif; ?>
					
					<input class="mtbxr-vimeo-url <?php echo $push; ?>" type="text" value="<?php esc_attr_e($value); ?>" name="<?php esc_attr_e($name); ?>[]" />
					<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-vimeo-drag" href="#"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
					<span class="mtbxr-vimeo-action">
						<a class="mtbxr-vimeo-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
					</span>
				</div>
				<?php
			endforeach;
		?>
	</div>
	<div class="clear"></div>
	<input class="mtbxr-vimeo-add button-secondary" data-post-id="<?php esc_attr_e($post_id); ?>" type="button" value="<?php _e('Add Vimeo Video', 'mtbxr'); ?>" />
	<br />
	<span class="note"><?php esc_attr_e($note);?></span>
	<div class="mtbxr-vimeo-skeleton">
		<div class="mtbxr-vimeo-video">
			<input class="mtbxr-vimeo-url" type="text" value="" name="<?php esc_attr_e($name); ?>_dummy[]" />
			<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-vimeo-drag" href="#"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
			<span class="mtbxr-vimeo-action">
				<a class="mtbxr-vimeo-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
			</span>
		</div>
	</div>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>