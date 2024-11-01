<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Time')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Time in '.__FILE__;
	}
	
	class Mtbxr_Time extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_value'] = ( isset($meta[ $this->definition['uid'] ]) ) ? $meta[ $this->definition['uid'] ] : '';
			for($h=1; $h<=12; ++$h){
				$vars['hours'][$h] = $h;
			}
	
			$vars['time'] = $this->timestamp_to_time($vars['saved_value']);
			$vars['name'] = $this->definition['uid'];
			$vars['label'] = $this->definition['label'];
			$vars['note'] = apply_filters('mtbxr_field_note', $this->definition['note'], $this->definition);
			$vars['field_class'] = $field_class;
			
			$vars['debug'] = '';
			if( MTBXR_DEBUG )
				$vars['debug'] = 'Value: '.mtbxr_debug( $vars['saved_value'] );
				
			parent::render_main_view( $vars );// Call parent function
	
		}
		
		function save($post_id){
			
			$uid = $this->definition['uid'];
			if(isset($_POST[$uid.'_delete'])){
				delete_post_meta($post_id, $uid);
				
			} else {
				if(isset($_POST[$uid])){ // Make sure post data exist
					
					$hour = ( isset($_POST[$uid]['hour']) ) ? (int)$_POST[$uid]['hour'] : 0 ;
					$minute = ( isset($_POST[$uid]['minute']) ) ? (int)$_POST[$uid]['minute'] : 0 ;
					$second = ( isset($_POST[$uid]['second']) ) ? (int)$_POST[$uid]['second'] : 0 ;
					$meridiem = ( isset($_POST[$uid]['meridiem']) ) ? $_POST[$uid]['meridiem'] : '' ;
					
					if( ($hour > 0) and ($meridiem != '') ){
						$timestamp = $this->time_to_timestamp($hour, $minute, $second, $meridiem);
						update_post_meta($post_id, $uid, $timestamp);
					}
					
				}
			}
		}
		
		function time_to_timestamp($hours, $minutes, $seconds, $meridiem){		
			$hours = sprintf('%02d',(int) $hours);
			$minutes = sprintf('%02d',(int) $minutes);
			$seconds = sprintf('%02d',(int) $seconds);
			$meridiem = (strtolower($meridiem)=='am') ? 'am' : 'pm';
			return date('U', strtotime("{$hours}:{$minutes}:{$seconds} {$meridiem}"));
		}
		
		// Returns array
		function timestamp_to_time($timestamp){
			if(empty($timestamp)){
				return array(			
					'hour' => '',
					'minute' => '',
					'second' => '',
					'meridiem' => ''
				);
			}
			return array(
				'hour' => date('g', $timestamp),
				'minute' => date('i', $timestamp),
				'second' => date('s', $timestamp),
				'meridiem' => date('a', $timestamp)
			);
		}
	}

endif;