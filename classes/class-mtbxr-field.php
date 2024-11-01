<?php if (!defined ('ABSPATH')) die ('Access not allowed.');

if(!class_exists('Mtbxr_Field')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Field in '.__FILE__;
	}
	
	/**
	* Base class for all fields
	*/
	class Mtbxr_Field {
		public $name; // Store this class' name
		public $definition; // Field definition
		public $vars; // Template vars. Hold vars that will be passes to views
		protected $builder_view_file; // Holds the view file of the builder
		
		function __construct($definition){
			$this->name = $this->_class_name();
			$this->builder_view_file = MTBXR_PATH.'views/fields/'.$this->get_file_name().'/builder.php';
			$this->main_view_file = MTBXR_PATH.'views/fields/'.$this->get_file_name().'/main.php';
			$this->definition = $definition;
			$vars = array();
		}
		
		// Show builder view of field
		public function render_builder_view( $vars ){
			$mtbxr_view = new Mtbxr_View( $this->builder_view_file );
			$mtbxr_view->set_vars( $vars );
			$mtbxr_view->render();
		}
		
		// Show main view of field
		public function render_main_view( $vars ){
			$mtbxr_view = new Mtbxr_View( $this->main_view_file );
			$mtbxr_view->set_vars( $vars );
			$mtbxr_view->render();
		}
		
		// Get file name minus prefix and .php suffix
		public function get_file_name(){
			return str_replace('_', '-', $this->_class_name());
		}
		
		/**
		* Save operation here. Implemention in child classes
		*/
		function save(){
			
		}
		
		/**
		* Basic sanitizer
		*/
		function sanitize_post_data($post_data, $sanitation=''){
			$post_data = trim($post_data);
			$save_data = wp_kses_post($post_data); // Default. Allow html tags that are allowed in post/page
			
			if('raw'==$sanitation){
				$save_data = $post_data; //Raw data no sanitation
			} else if('nohtml'==$sanitation){
				$save_data = sanitize_text_field($post_data);// Strip tags and full sanitation. 
			}
			return $save_data;
		}
		
		/**
		* Get class name performing needed computations
		*/
		function _class_name(){
			return str_replace('mtbxr_','',strtolower(get_class($this)));// Get our class name, make it lowercase, and remove prefix
		}
		
		/**
		* Get folder name performing needed computations
		*/
		function _folder_name($name){
			return str_replace('_','-', $name);
		}
	}

endif;