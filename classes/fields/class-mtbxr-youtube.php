<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Youtube')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Youtube in '.__FILE__;
	}
	
	class Mtbxr_Youtube extends Mtbxr_Field{
		
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
				
				if($video_id = $this->get_youtube_id($value)){
					$vars['thumbnails'][] = 'http://img.youtube.com/vi/'.$video_id.'/default.jpg';
				} else {
					$vars['thumbnails'][] = '';
				}
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
		
		function get_youtube_id($url){
			if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
				return false;
			}
			$parsed_url = parse_url($url);
			if(strpos($parsed_url['host'], 'youtube.com')!==false){
				if(strpos($parsed_url['path'], '/watch')!==false){ //regular url Eg. http://www.youtube.com/watch?v=-wtIMTCHWuI
					parse_str($parsed_url['query'], $parsed_str);
					if(isset($parsed_str['v']) and !empty($parsed_str['v'])){
						return $parsed_str['v'];
					}
				} else if(strpos($parsed_url['path'], '/v/')!==false){ //Eg. http://www.youtube.com/v/-wtIMTCHWuI?version=3&autohide=1
					$id = str_replace('/v/','',$parsed_url['path']);
					if( !empty($id) ){
						return $id;
					}
				}
			} else if(strpos($parsed_url['host'], 'youtu.be')!==false){ //Eg. http://youtu.be/-wtIMTCHWuI
				return str_replace('/','',$parsed_url['path']);
			}
			
			return 'false';
		}
	}

endif;