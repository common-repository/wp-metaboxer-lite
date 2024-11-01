<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Date')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Date in '.__FILE__;
	}
	
	class Mtbxr_Date extends Mtbxr_Field{
		
		function __construct($definition){
			parent::__construct($definition);	
		}
		
		// Show main view of field
		public function render_main_view($meta, $post_id, $field_class=''){
			$vars['saved_value'] = ( isset($meta[ $this->definition['uid'] ]) ) ? $meta[ $this->definition['uid'] ] : '';
			$vars['months'][0] = '';
			for($m=1; $m<=12; ++$m){
				$vars['months'][$m] = date('F',mktime(0,0,0,$m));
			}
	
			$vars['date'] = $this->timestamp_to_date($vars['saved_value']);
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
	
					if( ($month > 0) and ($day > 0) and ($year > 0)){
						$timestamp = mktime(0,0,0, $month, $day, $year);
						update_post_meta($post_id, $uid, $timestamp);
					}
					
				}
			}
		}
		
		
		// Returns array
		function timestamp_to_date($timestamp){
			if(empty($timestamp)){
				return array(
					'year' => '',
					'month' => '',
					'day' => ''
				);
			}
			return array(
				'year' => date('Y', $timestamp),
				'month' => date('n', $timestamp),
				'day' => date('j', $timestamp)
			);
		}
	}

endif;