<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Textarea')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Textarea in '.__FILE__;
	}
	
	class Mtbxr_Textarea extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		// Show main view of field
		public function render_main_view( $meta, $post_id, $field_class='' ){
			$saved_value = ( isset($meta[ $this->definition['uid'] ]) ) ? $meta[$this->definition['uid']] : '';
		
			if(!empty($saved_value)){
				$vars['value'] = $saved_value;
			} else {
				$vars['value'] = $this->definition['default'];
			}
			
			$vars['name'] = $this->definition['uid'];
			$vars['label'] = $this->definition['label'];
			$vars['note'] = apply_filters('mtbxr_field_note', $this->definition['note'], $this->definition);
			$vars['field_class'] = $field_class;
			
			$vars['debug'] = '';
			if( MTBXR_DEBUG )
				$vars['debug'] = 'Value: '.mtbxr_debug( $saved_value );
				
			parent::render_main_view( $vars );// Call parent function
			
		}
		
		function save($post_id){
			$uid = $this->definition['uid'];
			if(isset($_POST[$uid.'_delete'])){
				delete_post_meta($post_id, $uid);
			} else {
				if(isset($_POST[$uid])){ // Make sure post data exist
					$save_data = $this->sanitize_post_data($_POST[$uid], $this->definition['sanitation']);
					
					if(!empty($save_data)){
						update_post_meta($post_id, $uid, $save_data);
					}
				}
			}
		}
	}

endif;