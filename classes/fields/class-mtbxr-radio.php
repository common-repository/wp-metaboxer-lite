<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Radio')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Radio in '.__FILE__;
	}
	
	class Mtbxr_Radio extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_value'] = ( isset($meta[ $this->definition['uid'] ]) ) ? $meta[ $this->definition['uid']] : '';
	
			$vars['options'] = $this->definition['options'];
			if(isset($this->definition['data_source']) && !empty($this->definition['data_source']) && is_callable($this->definition['data_source']) ){
				$vars['options'] = call_user_func($this->definition['data_source']);
			}
			$vars['options'] = apply_filters('mtbxr_radio_options', $vars['options'], $this->definition);
			foreach($vars['options'] as $i=>$option){
				$vars['options'][$i]['checked'] = ($this->is_selected($option['value'], $vars['saved_value'])) ? 'checked="checked"' : '';
			}
			$vars['name'] = $this->definition['uid'];
			$vars['label'] = $this->definition['label'];
			$vars['note'] = apply_filters('mtbxr_field_note', $this->definition['note'], $this->definition);
			$vars['field_class'] = $field_class;
			
			$vars['debug'] = '';
			if( MTBXR_DEBUG )
				$vars['debug'] = mtbxr_debug( 'Value = '.$vars['saved_value'] );
			
			parent::render_main_view( $vars );// Call parent function
			
		}
		
		function save($post_id){
			
			$uid = $this->definition['uid'];
			if(isset($_POST[$uid.'_delete'])){
				delete_post_meta($post_id, $uid);
				
			} else {
				if(isset($_POST[$uid])){ // Make sure post data exist
					$save_data = $this->sanitize_post_data($_POST[$uid]);
					
					update_post_meta($post_id, $uid, $save_data);
					
				}
			}
		}
		
		function is_selected($needle, $saved_value){
			$options = array();
	
			if( !empty($saved_value) ){//selected from saved data
				if($saved_value==$needle){
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