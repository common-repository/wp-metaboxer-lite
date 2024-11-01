<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Select')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Select in '.__FILE__;
	}
	
	class Mtbxr_Select extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		// Show main view of field
		public function render_main_view( $meta, $post_id, $field_class='' ){
			$vars['saved_values'] = ( isset($meta[ $this->definition['uid'] ]) ) ? maybe_unserialize( $meta[ $this->definition['uid']] ) : array();
			$vars['options'] = $this->definition['options'];
	
			if(isset($this->definition['data_source']) && !empty($this->definition['data_source']) && is_callable($this->definition['data_source']) ){
				$vars['options'] = call_user_func($this->definition['data_source']);
			}
			$vars['options'] = apply_filters('mtbxr_select_options', $vars['options'], $this->definition);
			foreach($vars['options'] as $i=>$option){
				$vars['options'][$i]['selected'] = ($this->is_selected($option['value'], $vars['saved_values'])) ? 'selected="selected"' : '';
			}
			$vars['name'] = $this->definition['uid'];
			$vars['label'] = $this->definition['label'];
			$vars['note'] = apply_filters('mtbxr_field_note', $this->definition['note'], $this->definition);
			$vars['multiple'] = $this->definition['multiple'];
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
					foreach($_POST[$uid] as $selected_val){ // Allow multiple options selection
						$selected_val = $this->sanitize_post_data($selected_val, 'nohtml');
						$save_data[$uid][] = $selected_val;
					}
					update_post_meta($post_id, $uid, $save_data[$uid]);
				} else {
					delete_post_meta($post_id, $uid); // Delete meta from db if none exists
				}
			}
		}
		
		function is_selected($needle, $saved_values){
			
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