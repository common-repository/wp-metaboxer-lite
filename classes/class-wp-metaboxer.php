<?php
if(!class_exists('WP_Metaboxer')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class WP_Metaboxer in '.__FILE__;
	}
	
	/**
	* Creates metaboxes and fields using the definition data
	*/
	class WP_Metaboxer {
		public  $_meta_box;
		public $_page;
		private $_field_instances;
		
		/**
		 * Initializes the plugin
		 * 
		 * @param array $meta_box The definition data of the meta boxes and fields.
		 * @param string $page The post where the meta box is displayed.
		 */
		function __construct($meta_box, $page) {
			
			$this->_meta_box = $this->prep_meta_box_data($meta_box);
			
			if(empty($this->_meta_box['uid']) or empty($this->_meta_box['title']) ){
				return false;
			}
			
			$this->_page = $page;
			$this->_field_instances = array();
			
	
			if(isset($this->_meta_box['fields'])){
				foreach($this->_meta_box['fields'] as $field){
					
					$classname = $this->infer_class_name($field['type']); // Infer class name based on our field type
					if(class_exists($classname)){
						$instantiator = new ReflectionClass($classname); 
						$this->_field_instances[$field['uid']] = $instantiator->newInstance($field); // Create class instance and assign it
					}
			
				}
			}
			
			// Register admin styles and scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'register_wp_media' ), 9);
			add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ), 10);
			
			// Custom CSS class
			add_filter( "postbox_classes_{$page}_".$this->_meta_box['uid'], array( &$this, 'custom_postbox_class' ) );
			
			// Add metaboxes
			add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );
			
			// Hook to save
			add_action( 'save_post', array( &$this, 'save_post' ) );
			
			// Hacky way to change text in thickbox
			add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 10, 3 );
			
			// Modify html of image
			add_filter( 'image_send_to_editor', array( $this, 'image_send_to_editor'), 1, 8 );
			add_filter( 'media_send_to_editor', array( $this, 'media_send_to_editor'), 10, 3 );
			
			
		}
		
		/**
		 * Provide all default keys
		 */
		function prep_meta_box_data($meta_box){
			$defaults = array(
				'uid' => '',
				'title'=> '',
				'page' => '',
				'context' => '',
				'priority' => '',
				'fields' => array()
			);
			
			return wp_parse_args($meta_box, $defaults);
		}
		
		/**
         * Add js and css for WP media manager.
         */ 
        public function register_wp_media(){
            global $wp_version;
            
            if($this->_page == get_post_type()){ /* Load only scripts here and not on all admin pages */
                
                if ( version_compare( $wp_version, '3.5', '<' ) ) { // Use old media manager
                    
                    wp_enqueue_style('thickbox');
                    
                    wp_enqueue_script('media-upload');
                    wp_enqueue_script('thickbox');
                    
                } else {
                    // Required media files for new media manager. Since WP 3.5+
                    wp_enqueue_media();
                }
            }
        }
		
		/**
		 * Enqueue scripts and css
		 */
		function register_admin_scripts($hook){
			global $wp_version;
			
			if(get_post_type()==$this->_page || $hook=='link.php') {// Use only if needed. get_post_type() does not return value on links so we check $hook.
				if ( version_compare( $wp_version, '3.5', '<' ) ) { // Use old media manager
                    $new_media_gallery = false; 
                } else {
                    $new_media_gallery = true;
                }
				/*** CSS ***/
				wp_enqueue_style( 'mtbxr-jquery-ui', MTBXR_URL.'css/jquery-ui/smoothness/jquery-ui-1.8.23.custom.css', array(), MTBXR_VERSION ); // USed by: date
			
				wp_enqueue_style( 'mtbxr-fields', MTBXR_URL.'css/fields.css', array(), MTBXR_VERSION );
				
				/*** Scripts ***/
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				
				wp_register_script( 'mtbxr-fields', MTBXR_URL.'js/fields.js', array('jquery'), MTBXR_VERSION );
				wp_localize_script( 'mtbxr-fields', 'mtbxr',
                    array(
                        'new_media_gallery' => $new_media_gallery,
						'media_window_title' => __('Choose Media', 'mtbxr'),
						'media_button_caption'=> __('Use Media File', 'mtbxr')
					)
                );
				wp_enqueue_script( 'mtbxr-fields' );
			}
		}
		
		/**
		 * Adds custom class to our metaboxes for custom styling purposes
		 */
		function custom_postbox_class($classes){
			$classes[] = 'mtbxr-meta-box';
			return $classes;
		}
		
		/**
		 * Add the metabox
		 */
		function add_meta_box(){
			add_meta_box(
				$this->_meta_box['uid'],
				$this->_meta_box['title'],
				array( &$this, 'render_meta_box' ),
				$this->_page,
				$this->_meta_box['context'],
				$this->_meta_box['priority']
			);
		}
		
		/**
		 * Show the metabox
		 */
		function render_meta_box($post){
			
			$post_id = (isset($post->ID)) ? $post->ID : $post->link_id;
			$meta = mtbxr_all($post_id);
			?>
			<input type="hidden" name="mtbxr_<?php echo $this->_meta_box['uid']; ?>_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
			<?php
			$count=0;
			foreach($this->_field_instances as $uid=>$field){
				$extra_css = (($count+1)>=count($this->_field_instances)) ? ' mtbxr-field-last' : '';
	
				$field->render_main_view($meta, $post_id, $extra_css); // Prepare our template vars and show template
				$count++;
			}
			if(MTBXR_DEBUG) {
				echo mtbxr_debug( mtbxr_all() );
				echo mtbxr_debug( mtbxr_get_all_metaboxes() );
			}
				
		}
		
		/**
		 * Hook to save post
		 */
		function save_post($post_id){
	
			// Verify nonce
			$nonce_name = 'mtbxr_'.$this->_meta_box['uid'].'_nonce';
			if (!empty($_POST[$nonce_name])) {
				if (!wp_verify_nonce($_POST[$nonce_name], basename(__FILE__))) {
					return $post_id;
				}
			} else {
				return $post_id; // Make sure we cancel on missing nonce!
			}
			
			// Reject autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}
			
			$meta = mtbxr_all($post_id);
			
			// Save it. Loop thru each fields and apply correct save routines
			if(!empty($this->_field_instances )){
				foreach($this->_field_instances as $uid=>$field){
					$field->save($post_id);
				}
			}
			
			remove_action('save_post', array( &$this, 'save_post' )); // Run save once
		}
		
		/**
		 * Replace non alphanumeric with underscore. For format that is valid for PHP variable names, HTML ID, attr name
		 */
		function sanitize_uid( $key ) {
			$key = strtolower( $key );
			$key = preg_replace( '/[^a-z0-9]+/', '_', $key ); // Replace non alphanumeric with underscores
			return preg_replace('/[_]+$/', '', $key); // Remove last underscore if there is any. Eg. "my_var_" becomes "my_var"
		}
		
		/**
		 * Replace text in thickbox
		 */
		function replace_text_in_thickbox($translation, $text, $domain ) {
			$http_referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$req_referrer = isset($_REQUEST['referer']) ? $_REQUEST['referer'] : '';
			if(strpos($http_referrer, 'mtbxr')!==false or $req_referrer=='mtbxr') {
				if ( 'default' == $domain and 'Insert into Post' == $text )
				{
					return 'Insert to Metabox';
				}
			}
			return $translation;
		}
		
		
		/**
		 * Add attachment ID as html5 data attr in thickbox
		 */
		function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
			
			if(strpos($html, '<img data-id="')===false){
				$html = str_replace('<img', '<img data-id="'.$id.'" ', $html);
			}
			if(strpos($html, '<a data-id="')===false){
				$html = str_replace('<a', '<a data-id="'.$id.'" ', $html);
			}
			return $html;
		}
		
		/**
		 * Add attachment ID as html5 data attr in thickbox
		 */
		function media_send_to_editor( $html, $attachment_id, $attachment){
			
			if(strpos($html, '<a data-id="')===false){
				$html = str_replace('<a', '<a data-id="'.$attachment_id.'" ', $html);
			}
			return $html;
		}
		
		/**
		 * Guess our class name
		 */
		function infer_class_name($name){
			$name = 'Mtbxr_'.ucwords($name);
			return $name;
		}
	
	}
	
endif;