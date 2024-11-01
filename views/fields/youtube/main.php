<?php if (!defined ('ABSPATH')) die ('Access not allowed.'); ?>
<div class="mtbxr-field <?php esc_attr_e($field_class); ?>">
	<?php echo $debug; ?>
	<label for="<?php esc_attr_e($name); ?>"><?php esc_attr_e($label); ?></label>
	<div class="mtbxr-youtube-videos">
		<?php foreach($textboxes as $i=>$value): ?>
				<div class="mtbxr-youtube-video">
					
					<?php
					$push = '';
					if($this->get_youtube_id($value) && isset($thumbnails[$i]) && !empty($thumbnails[$i])):
						$push = 'push';
					?>
						<a class="mtbxr-youtube-preview" href="<?php esc_attr_e($value); ?>" target="_blank">
							<img src="<?php echo $thumbnails[$i]; ?>" alt="preview" />
						</a>
					<?php endif; ?>
					
					<input class="mtbxr-youtube-url <?php echo $push; ?>" type="text" value="<?php esc_attr_e($value); ?>" name="<?php esc_attr_e($name); ?>[]" />
					<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-youtube-drag" href="#"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
					<span class="mtbxr-youtube-action">
						<a class="mtbxr-youtube-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
					</span>
				</div>
				<?php
			endforeach;
		?>
	</div>
	<div class="clear"></div>
	<input class="mtbxr-youtube-add button-secondary" data-post-id="<?php esc_attr_e($post_id); ?>" type="button" value="<?php _e('Add Youtube Video', 'mtbxr'); ?>" />
	<br />
	<span class="note"><?php esc_attr_e($note);?></span>
	<div class="mtbxr-youtube-skeleton">
		<div class="mtbxr-youtube-video">
			<input class="mtbxr-youtube-url" type="text" value="" name="<?php esc_attr_e($name); ?>_dummy[]" />
			<a title="<?php _e('Drag', 'mtbxr'); ?>" class="mtbxr-youtube-drag" href="#"><span class="dot1"></span><span class="dot2"></span><span class="dot3"></span></a>
			<span class="mtbxr-youtube-action">
				<a class="mtbxr-youtube-delete" href="#"><?php _e('Delete', 'mtbxr'); ?></a>
			</span>
		</div>
	</div>
	
	<label for="<?php esc_attr_e($name); ?>_delete" class="mtbxr-value-delete">
		<span><?php _e('Empty Data', 'mtbxr'); ?></span>
		<input type="checkbox" id="<?php esc_attr_e($name); ?>_delete" value="1" name="<?php esc_attr_e($name); ?>_delete" />
	</label>
</div>