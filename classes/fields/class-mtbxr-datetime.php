<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Datetime')):

	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Datetime in '.__FILE__;
	}
	
	class Mtbxr_Datetime extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_value'] = ( isset($meta[ $this->definition['uid'] ]) ) ? $meta[ $this->definition['uid'] ] : '';
			for($m=1; $m<=12; ++$m){
				$vars['months'][$m] = date('F',mktime(0,0,0,$m));
			}
			for($h=1; $h<=12; ++$h){
				$vars['hours'][$h] = $h;
			}
			$vars['datetime'] = $this->timestamp_to_datetime($vars['saved_value']);
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
					
					$month = ( isset($_POST[$uid]['month']) ) ? (int)$_POST[$uid]['month'] : 0 ;
					$day = ( isset($_POST[$uid]['day']) ) ? (int)$_POST[$uid]['day'] : 0 ;
					$year = ( isset($_POST[$uid]['year']) ) ? (int)$_POST[$uid]['year'] : 0 ;
					
					$hour = ( isset($_POST[$uid]['hour']) ) ? (int)$_POST[$uid]['hour'] : 0 ;
					$minute = ( isset($_POST[$uid]['minute']) ) ? (int)$_POST[$uid]['minute'] : 0 ;
					$second = ( isset($_POST[$uid]['second']) ) ? (int)$_POST[$uid]['second'] : 0 ;
					$meridiem = ( isset($_POST[$uid]['meridiem']) ) ? $_POST[$uid]['meridiem'] : '' ;
					
					if( ($month > 0) and ($day > 0) and ($year > 0) and ($hour > 0) and ($meridiem != '') ){
						$timestamp = $this->datetime_to_timestamp($year, $month, $day, $hour, $minute, $second, $meridiem);
						update_post_meta($post_id, $uid, $timestamp);
					}
					
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
		
		function datetime_to_timestamp($year, $month, $day, $hours, $minutes, $seconds, $meridiem){
			$year = (int) $year;
			$month = sprintf('%02d',(int) $month);
			$day = sprintf('%02d',(int) $day);
			
			$hours = sprintf('%02d',(int) $hours);
			$minutes = sprintf('%02d',(int) $minutes);
			$seconds = sprintf('%02d',(int) $seconds);
			$meridiem = (strtolower($meridiem)=='am') ? 'am' : 'pm';
			return date('U', strtotime("{$year}/{$month}/{$day} {$hours}:{$minutes}:{$seconds} {$meridiem}"));
		}
		
		// Returns array
		function timestamp_to_datetime($timestamp){
			if(empty($timestamp)){
				return array(
					'year' => '',
					'month' => '',
					'day' => '',
					
					'hour' => '',
					'minute' => '',
					'second' => '',
					'meridiem' => ''
				);
			}
			return array(
				'year' => date('Y', $timestamp),
				'month' => date('n', $timestamp),
				'day' => date('j', $timestamp),
				
				'hour' => date('g', $timestamp),
				'minute' => date('i', $timestamp),
				'second' => date('s', $timestamp),
				'meridiem' => date('a', $timestamp)
			);
		}
	}

endif;