<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Vimeo')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Vimeo in '.__FILE__;
	}
	
	class Mtbxr_Vimeo extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_values'] = ( isset($meta[ $this->definition['uid'] ]) ) ? maybe_unserialize( $meta[ $this->definition['uid']] ) : array();
			
			$vars['textboxes'] = array();
			$vars['thumbnails'] = array();
			foreach($vars['saved_values'] as $value){
				$vars['textboxes'][] = $value;
				
				$vimeo_thumb = $this->get_vimeo_thumb_uri($value); 
				$vars['thumbnails'][] = $vimeo_thumb;
				
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
						$save_data[] = esc_url_raw($value);
					}
					update_post_meta($post_id, $uid, $save_data);
				} else {
					delete_post_meta($post_id, $uid); // Delete meta from db if none exists
				}
			}
		}
		
		function is_vimeo_url($url){
			$parsed_url = parse_url($url);
			if ($parsed_url['host'] == 'vimeo.com'){
				$vimeo_id = ltrim( $parsed_url['path'], '/');
				if (is_numeric($vimeo_id)) {
					return true;
				}
			}
			return false;
		}
		
		function get_vimeo_id($url){
			$parsed_url = parse_url($url);
			if ($parsed_url['host'] == 'vimeo.com'){
				$vimeo_id = ltrim( $parsed_url['path'], '/');
				if (is_numeric($vimeo_id)) {
					return $vimeo_id;
				}
			}
			return false;
		}
		
		function get_vimeo_thumb_uri($url){
			if($vimeo_id = $this->get_vimeo_id($url) ){
				$vimeo = unserialize( file_get_contents('http://vimeo.com/api/v2/video/'.$vimeo_id.'.php') );
				if( isset($vimeo[0]['thumbnail_medium']) ){
					return $vimeo[0]['thumbnail_medium'];
				}
			}
			return '';
		}
	}

endif;