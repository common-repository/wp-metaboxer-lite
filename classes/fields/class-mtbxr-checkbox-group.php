<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Checkbox_Group')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Checkbox_Group in '.__FILE__;
	}
	
	class Mtbxr_Checkbox_Group extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);
		}
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_values'] = ( isset($meta[ $this->definition['uid'] ]) ) ? maybe_unserialize( $meta[ $this->definition['uid']] ) : array();
		
			$vars['options'] = $this->definition['options'];
			if(isset($this->definition['data_source']) && !empty($this->definition['data_source']) && is_callable($this->definition['data_source']) ){
				$vars['options'] = call_user_func($this->definition['data_source']);
			}
			
			$vars['options'] = apply_filters('mtbxr_checkbox_group_options', $vars['options'], $this->definition);
			foreach($vars['options'] as $i=>$option){
				$vars['options'][$i]['checked'] = ($this->is_checked($option['value'], $vars['saved_values'])) ? 'checked="checked"' : '';
			}
			$vars['name'] = $this->definition['uid'];
			$vars['label'] = $this->definition['label'];
			$vars['note'] = apply_filters('mtbxr_field_note', $this->definition['note'], $this->definition);
			$vars['field_class'] = $field_class;
			
			$vars['debug'] = '';
			if( MTBXR_DEBUG )
				$vars['debug'] = 'Value: '.mtbxr_debug( $vars['saved_values'] );
				
			parent::render_main_view( $vars );// Call parent function
			
		}
		
		function save($post_id){
	
			$save_data = array();
			
			$uid = $this->definition['uid'];
			if(isset($_POST[$uid.'_delete'])){
				delete_post_meta($post_id, $uid); // Delete
				
			} else {
				if(isset($_POST[$uid])){ // Make sure post data exist
					foreach($_POST[$uid] as $checked_val){
						$save_data[] = $this->sanitize_post_data($checked_val);
					}
					update_post_meta($post_id, $uid, $save_data);
				} else {
					delete_post_meta($post_id, $uid); // Delete meta from db if none selected
				}
			}
			
		}
		
		function is_checked($needle, $saved_values){
			
			if( !empty($saved_values) ){//selected from saved data
				if(in_array($needle, $saved_values)){
					return true;
				}
			} else { //selected from default
				if($needle == $this->definition['default']){
					return true;
				}
			}
			return false;
		}
	}

endif;