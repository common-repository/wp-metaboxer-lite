<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Sortable_Files')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Sortable_Files in '.__FILE__;
	}
	
	class Mtbxr_Sortable_Files extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);
		}
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_values'] = ( isset($meta[ $this->definition['uid'] ]) ) ? maybe_unserialize( $meta[ $this->definition['uid']] ) : array();
			
			$vars['files'] = array();
			foreach($vars['saved_values'] as $attachment_id){
				$file_url = wp_get_attachment_url( $attachment_id );
				$vars['files'][] = array($attachment_id, $file_url);
			}
			$vars['name'] = $this->definition['uid'];
			$vars['label'] = $this->definition['label'];
			$vars['note'] = apply_filters('mtbxr_field_note', $this->definition['note'], $this->definition);
			$vars['field_class'] = $field_class;
			$vars['post_id'] = $post_id;
			
			$vars['debug'] = '';
			if( MTBXR_DEBUG )
				$vars['debug'] = 'Value: '.mtbxr_debug( $vars['saved_values'] );
				
			parent::render_main_view( $vars );// Call parent function
			
		}
		
		function save($post_id){
			$save_data = array();
			$uid = $this->definition['uid'];
			if(isset($_POST[$uid.'_delete'])){
				delete_post_meta($post_id, $uid);
				
			} else {
				if( isset($_POST[$uid]) ){
					foreach($_POST[$uid] as $value){
						$save_data[] = (int) $value;
					}
					update_post_meta($post_id, $uid, $save_data);
				} else {
					delete_post_meta($post_id, $uid); // Delete meta from db if none exists
				}
			}
		}
	}

endif;