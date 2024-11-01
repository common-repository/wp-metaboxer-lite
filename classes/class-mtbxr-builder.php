<?php
if(!class_exists('Mtbxr_Builder')):
	
	if(MTBXR_DEBUG){
		$mtbxr_debug_info[] = 'Included class Mtbxr_Builder in '.__FILE__;
	}
	
	/**
	* Class for building metabox forms via WP admin interface
	*
	* @property boolean $debug True to enable debug mode. Default to false.
	*/
	class Mtbxr_Builder {
		
		protected $field_instances;
		private $debug;
		
		/**
		* Constructor function
		*/
		function __construct() {
	
			// Add admin menus
			add_action( 'init', array( &$this, 'create_post_types' ) );
			
			// Register admin styles and scripts
			add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ), 10);
			
			// Update the messages for our custom post make it appropriate 
			add_filter('post_updated_messages', array( &$this, 'post_updated_messages' ) );
			
			// Control post messages
			add_filter('redirect_post_location', array( &$this, 'redirect_post_location' ) );
			
			// Add metabox
			add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
			
			// Custom columns
			add_action( 'manage_mtbxr_metabox_posts_custom_column', array( $this, 'custom_column' ), 10, 2);
			add_filter( 'manage_edit-mtbxr_metabox_columns', array( $this, 'builder_columns') );
			
			// Hook to save
			add_action( 'save_post', array( &$this, 'save_post' ) );
			
			// Hook to admin head
			add_action( 'admin_head', array( &$this, 'admin_head' ) );
			
			// Use in footer
			add_action( 'admin_footer', array( &$this, 'admin_footer' ) );
			
			// Register are messaging system
			add_action('admin_notices', array( &$this, 'flash_messages' ) );
			
			// Add our custom admin menu
			add_action('admin_menu', array( $this, 'admin_menu' ) );
			
			// Add hook for ajax operations if logged in
			add_action( 'wp_ajax_mtbxr_get_youtube_thumb', array( $this, 'mtbxr_get_youtube_thumb' ) );
			
			// Add hook for ajax operations if logged in
			add_action( 'wp_ajax_mtbxr_get_vimeo_thumb', array( $this, 'mtbxr_get_vimeo_thumb' ) );
	
		}
		
		/**
		* Callback for creating the mtbxr_metabox post type for the builder
		*/
		function create_post_types() {
			register_post_type( 'mtbxr_metabox',
				array(
					'labels' => array(
						'name' => __('WP Metaboxer', 'mtbxr'),
						'singular_name' => __('WP Metaboxer', 'mtbxr'),
						'add_new' => __('Add Metabox', 'mtbxr'),
						'add_new_item' => __('Add New Metabox', 'mtbxr'),
						'edit_item' => __('Edit Metabox', 'mtbxr'),
						'new_item' => __('New Metabox', 'mtbxr'),
						'view_item' => __('View Metabox', 'mtbxr'),
						'search_items' => __('Search Metaboxse', 'mtbxr'),
						'not_found' => __('No metaboxes found', 'mtbxr'),
						'not_found_in_trash' => __('No metaboxes found in Trash', 'mtbxr')
					),
					'supports' => array('title'),
					'public' => false,
					'exclude_from_search' => true,
					'show_ui' => true,
					'menu_position' => 100
				)
			);
		}
		
		
		/**
		* Callback adding css and scripts for the builder
		*
		* @param string $hook The name of the current admin page file
		*/
		function register_admin_scripts($hook){
			if(get_post_type()=='mtbxr_metabox' or $hook == 'mtbxr_metabox_page_mtbxr_export'){
				wp_enqueue_style( 'mtbxr-builder', MTBXR_URL.'css/builder.css', array(), MTBXR_VERSION );
				
				wp_dequeue_script( 'autosave' ); // Disable autosave
				wp_enqueue_script('jquery-ui-sortable'); // Sortables
				
				wp_enqueue_script( 'jquery-cookie', MTBXR_URL.'js/builder.js', array('jquery'), MTBXR_VERSION );
				wp_enqueue_script( 'mtbxr-builder', MTBXR_URL.'js/jquery.cookie.js', array('jquery'), MTBXR_VERSION );
			}
		}
		
		/**
		* Callback for displaying custom metaboxes for the builder
		*/
		function add_meta_boxes(){
			add_meta_box(
				'mtbxr-metabox-fields',
				__('Fields', 'mtbxr'),
				array( &$this, 'render_fields_meta_box' ),
				'mtbxr_metabox' ,
				'normal',
				'high'
			);
			add_meta_box(
				'mtbxr-metabox-properties',
				__('Properties', 'mtbxr'),
				array( &$this, 'render_properties_meta_box' ),
				'mtbxr_metabox' ,
				'side',
				'default'
			);
		}
		
		/**
		* Callback for rendering the fields metabox of the builder
		*
		* @param object $post The object containing the current post data
		*/
		function render_fields_meta_box($post){
			
			if(MTBXR_DEBUG)
				echo mtbxr_debug_details();
				
			$meta = mtbxr_all($post->ID);
			$fields = (isset($meta['mtbxr_metabox']['fields'])) ? $meta['mtbxr_metabox']['fields'] : '';
			
			?>
			<input type="hidden" name="mtbxr_builder_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
			<div class="mtbxr-admin-workspace">
				<div class="mtbxr-admin-toolbar">
					<input type="button" class="mtbxr-btn-textbox button-secondary" value="Textbox" data-type="textbox" />
					<input type="button" class="mtbxr-btn-textarea button-secondary" value="Textarea" data-type="textarea" />
					<input type="button" class="mtbxr-btn-select button-secondary" value="Select" data-type="select" />
					<input type="button" class="mtbxr-btn-checkbox button-secondary" value="Checkbox" data-type="checkbox" />
					<input type="button" class="mtbxr-btn-radio button-secondary" value="Radio" data-type="radio" />
					<input type="button" class="mtbxr-btn-date button-secondary" value="Date" data-type="date" />
				</div>
				<div class="mtbxr-admin-sortables">
					<?php if($fields){
						$defaults = array(
							'uid'=>'',
							'type'=>'',
							'label'=>'',
							'default'=>'',
							'note'=>'',
							'sanitation'=>'',
							'options'=>'',
							'multiple'=>'',
							'data_source'=>''
						);
						foreach($fields as $index=>$field){
							$classname = 'Mtbxr_'.ucwords( $field['type'] ); // Infer class name based on our field type
							if(class_exists($classname)){
								$instantiator = new ReflectionClass($classname); 
								$this->field_instances[ $field['uid'] ] = $instantiator->newInstance( $field ); // Create class instance and assign it
							}
							$vars = wp_parse_args($field, $defaults); //Assign default variables to view file.
							$vars['index'] = $index; // Assign index
							$vars['debug'] = '';
							if(MTBXR_DEBUG)
								$vars['debug'] = mtbxr_debug($vars);
								
							$this->field_instances[ $field['uid'] ]->render_builder_view( $vars );
						}
					}
					?>
				</div>
			</div>
			<?php
			if(MTBXR_DEBUG)
				echo mtbxr_debug($fields);
		}
		
		
		/**
		* Callback for rendering the properties metabox of the builder
		*
		* @param object $post The object containing the current post data
		*/
		function render_properties_meta_box($post){
			
			$meta = mtbxr_all($post->ID);
			$pages = (isset($meta['mtbxr_metabox']['page'])) ? $meta['mtbxr_metabox']['page'] : array();
			$context = (isset($meta['mtbxr_metabox']['context'])) ? $meta['mtbxr_metabox']['context'] : '';
			$priority = (isset($meta['mtbxr_metabox']['priority'])) ? $meta['mtbxr_metabox']['priority'] : '';
			?>
			<input type="hidden" name="mtbxr_builder_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
			<div class="mtbxr-property-field">
				<label><?php _e('Show Metabox On:', 'mtbxr'); ?> <a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> </label>
				<?php
				$post_types = $this->post_types();
				if($post_types){
					foreach($post_types as $post_type=>$name){
						?>
						<input <?php echo (in_array($post_type, $pages)) ? 'checked="checked"' : ''; ?> type="checkbox" value="<?php echo $post_type;?>" name="mtbxr[page][]" id="mtbxr-type-<?php echo $post_type;?>" />
						<label class="mtbxr-checkbox-label" for="mtbxr-type-<?php echo $post_type;?>"><?php echo ucwords($name);?></label> <br />
						<?php
					}
				}
				?>
				
			</div>
			<div class="mtbxr-property-field">
				<label for="mtbxr-context"><?php _e('Metabox Position:', 'mtbxr'); ?></label>
				<select id="mtbxr-context" name="mtbxr[context]">
					<option <?php echo ($context=='normal') ? 'selected="selected"' : ''; ?> value="normal"><?php _e('Normal', 'mtbxr'); ?></option>
					<option <?php echo ($context=='advanced') ? 'selected="selected"' : ''; ?> value="advanced"><?php _e('Advanced', 'mtbxr'); ?></option>
					<option <?php echo ($context=='side') ? 'selected="selected"' : ''; ?> value="side"><?php _e('Side', 'mtbxr'); ?></option>
				</select>
				<a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a> 
			</div>
			<div class="mtbxr-property-field last">
				<label for="mtbxr-priority"><?php _e('Metabox Priority:', 'mtbxr'); ?></label>
				<select id="mtbxr-priority" name="mtbxr[priority]">
					<option <?php echo ($priority=='default') ? 'selected="selected"' : ''; ?> value="default"><?php _e('Default', 'mtbxr'); ?></option>
					<option <?php echo ($priority=='core') ? 'selected="selected"' : ''; ?> value="core"><?php _e('Core', 'mtbxr'); ?></option>
					<option <?php echo ($priority=='high') ? 'selected="selected"' : ''; ?> value="high"><?php _e('High', 'mtbxr'); ?></option>
					<option <?php echo ($priority=='low') ? 'selected="selected"' : ''; ?> value="low"><?php _e('Low', 'mtbxr'); ?></option>
				</select><a href="#" class="mtbxr-help"><?php _e('Help', 'mtbxr'); ?></a>
			</div>
			<?php
			if(MTBXR_DEBUG) {
				unset($meta['mtbxr_metabox']['fields']);
				echo mtbxr_debug($meta['mtbxr_metabox']);
			}
		}
		
		/**
		* Callback for custom admin notices for the builder
		*
		* @param array $messages Array containing the messages to process
		*/
		function post_updated_messages($messages){
			global $post, $post_ID;
			$messages['mtbxr_metabox'] = array(
				0  => '',
				1  => sprintf( __( 'Metabox updated.', 'mtbxr' ), $post->post_name),
				2  => __( 'Custom field updated.', 'mtbxr' ),
				3  => __( 'Custom field deleted.', 'mtbxr' ),
				4  => __( 'Metabox updated.', 'mtbxr' ),
				5  => __( 'Metabox updated.', 'mtbxr' ),
				6  => sprintf( __( 'Metabox created.', 'mtbxr' ), $post->post_name),
				7  => __( 'Metabox saved.', 'mtbxr' ),
				8  => __( 'Metabox updated.', 'mtbxr' ),
				9  => __( 'Metabox updated.', 'mtbxr' ),
				10 => __( 'Metabox updated.', 'mtbxr' )
			);
			return $messages;
		}
		
		/**
		* Modify columns
		*/
		function builder_columns($columns) {
			unset($columns['title'], $columns['page'], $columns['date']);
			$columns['title']= __('Metabox Title', 'mtbxr');
			$columns['page']= __('Shown On', 'mtbxr');
			$columns['date']= __('Date', 'mtbxr');
			return $columns;
		}
		
		/**
		* Custom columns
		*/
		function custom_column( $column_name, $post_id ){
			if ($column_name == 'page') {
				$meta = mtbxr_val('mtbxr_metabox', $post_id);
				if( isset($meta['page']) and is_array($meta['page']) ){
					echo ucwords(implode(', ', $meta['page']));
				}
			}  
		}
		
		/**
		* Callback when saving post
		*
		* @param int $post_id The current ID of the post being saved.
		*/
		function save_post($post_id){
			
			// Verify nonce
			if (!empty($_POST['mtbxr_builder_nonce'])) {
				if (!wp_verify_nonce($_POST['mtbxr_builder_nonce'], basename(__FILE__))) {
					return $post_id;
				}
			} else {
				return $post_id; // Make sure we cancel on missing nonce!
			}
	
			// Reject autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}
			
			$save_data = '';
			
			// Perform sanity check
			if(isset($_POST['mtbxr'])){
				$save_data = $_POST['mtbxr'];
				$id = get_current_user_id();
				$messages = array();
				if(isset($_POST['mtbxr']['fields'])){
					foreach($_POST['mtbxr']['fields'] as $i=>$field){
						
						if(isset($field['type'])){
							$save_data['fields'][$i]['type'] = sanitize_text_field($field['type']);
						}
						
						if(!isset($field['label']) or trim($field['label'])==''){
							$messages[$id]['errors']['label'] = '<p>Label should not be blank.</p>';
						} else {
							$save_data['fields'][$i]['label'] = sanitize_text_field($field['label']);
						}
						
						if(!isset($field['uid']) or trim($field['uid'])==''){
							$messages[$id]['errors']['uid'] = '<p>Unique Key should not be blank.</p>';
						} else {
							$save_data['fields'][$i]['uid'] = $this->sanitize_uid($field['uid']);
						}
						
						if(isset($field['options'])){
							$save_data['fields'][$i]['options'] = $field['options'];
						}
						
						if(isset($field['multiple'])){
							$save_data['fields'][$i]['multiple'] = sanitize_text_field($field['multiple']);
						}
						
						if(isset($field['default'])){
							$save_data['fields'][$i]['default'] = wp_kses_post($field['default']);
						}
						
						if(isset($field['note'])){
							$save_data['fields'][$i]['note'] = wp_kses_post($field['note']);
						}
						
						if(isset($field['data_source'])){
							$save_data['fields'][$i]['data_source'] = sanitize_text_field($field['data_source']);
						}
						
					}
					set_transient( 'mtbxr_messages', $messages, 60 );
				}
				if(empty($messages)){
					update_post_meta($post_id, 'mtbxr_metabox', $save_data);
				}
				
			}
			
		}
		
		/**
		 * Shoe messages or errors on post save
		 *
		 */
		function flash_messages(){
			$id = get_current_user_id();
			$messages = get_transient( 'mtbxr_messages' );
			if(isset($messages[$id]['errors'])){
				echo '<div class="error"> <p>Fields not saved due to error(s):</p>';
				foreach($messages[$id]['errors'] as $error){
					echo $error;
				}
				echo '</div>';
			}
			if(isset($messages[$id]['messages'])){
				foreach($messages[$id]['messages'] as $message){
					echo '<div class="updated">'.$message.'</div>';
				}
			}
			if(get_post_type()=='mtbxr_metabox'){
			?>
			<div class="updated" id="message"><p>You are using the free version of WP Metaboxer. Upgrade to <a target="_blank" href="http://codecanyon.net/item/wp-metaboxer/3160095">full version</a> with a lot more features.</p></div>
			<?php
			}
			delete_transient( 'mtbxr_messages' );
		}
		
		/**
		 * Function to replace $_GET['message]
		 *
		 * @param string $location URL to redirect to.
		 */
		function redirect_post_location($location){
			$messages = get_transient( 'mtbxr_messages' );
			if($messages){
				$location = remove_query_arg('message',$location);
				$location = add_query_arg('message', 11, $location);// Hide default messaging
			}
			return $location;
		}
		
		/**
		 * Function to replace non alphanumeric with underscore.
		 *
		 * Return format that is valid for PHP variable names, HTML ID, attr name
		 *
		 * @param string $key The string to sanitize.
		 */
		function sanitize_uid( $key ) {
			$key = strtolower( $key );
			$key = preg_replace( '/[^a-z0-9]+/', '_', $key ); // Replace non alphanumeric with underscores
			return preg_replace('/[_]+$/', '', $key); // Remove last underscore if there is any. Eg. "my_var_" becomes "my_var"
		}
		
		/**
		* Function that list the selectable post types for the builder
		*/
		function post_types(){
			$post_types = array();
			$args=array(
				'_builtin' => false // Get custom post types only
			);
			// Built-in post types
			$post_types['page']='Page';
			$post_types['post']='Post';
			//$post_types[]='link'; No support for links yet
			$custom_types = get_post_types($args, 'names');
			if(isset($custom_types['mtbxr_metabox']))
				unset($custom_types['mtbxr_metabox']); // Exclude meh self!
				
			return array_merge($post_types, $custom_types);// Merge post types
		}
		
		function admin_head(){
			?>
			<script type="text/javascript">
				window.admin_url = '<?php echo get_admin_url();?>';
			</script>
			<?php
		}
		
		/**
		* Callback for admin footer
		*/
		function admin_footer(){
			if(get_post_type()=='mtbxr_metabox'){
				$defaults = array(
					'uid'=>'',
					'type'=>'',
					'label'=>'',
					'default'=>'',
					'note'=>'',
					'sanitation'=>'',
					'options'=>array(
						array(
							'value'=>'',
							'text'=>''
						)
					),
					'multiple'=>'',
					'data_source'=>''
				);
				$vars = array(
					'ds'=>DIRECTORY_SEPARATOR,
					'index'=>'{index}',
					'debug'=>''
				);
				$vars = array_merge($defaults, $vars);
				$field_class_files = $this->get_field_class_list();
				?>
				<div id="mtbxr-builder-skeletons" style="<?php echo (MTBXR_DEBUG) ? '' : 'display:none;' ; ?>">
					<?php foreach($field_class_files as $class_file):
						$classname = str_replace('class-mtbxr-', '', $class_file );
						$classname = str_replace('.php', '', $classname );
						$defaults['type'] = $classname;
						$classname = str_replace('-', ' ', $classname );
						$classname = ucwords( $classname );
						$classname = str_replace(' ', '_', $classname );
						$classname = 'Mtbxr_'.$classname; // Infer class name based on our field type
						if(class_exists($classname)){
							$instantiator = new ReflectionClass($classname); 
							$field = $instantiator->newInstance( $defaults ); // Create class instance and assign it
						}
					?>
					<div class="<?php echo $field->definition['type']; ?>">
						<?php $field->render_builder_view( $vars );; ?>	
					</div>
					<?php endforeach; ?>
				</div>
			<?php
			}
		}
		
		/**
		* Get list of names of class files
		*/
		function get_field_class_list(){
			$ds = DIRECTORY_SEPARATOR;
			$class_list = array();
			$target_folder = MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields';
			
			if(is_dir($target_folder)){
				if($fields_dir = scandir($target_folder)){
					foreach($fields_dir as $file){
						if($file!='.' && $file !='..'){
							if(is_file($target_folder.$ds.$file)){
								$class_list[] = $file;
							}
						}
					}
				}
			}
			return $class_list;
		}
		
		/**
		* Ajax function for youtube thumbnail
		*/
		function mtbxr_get_youtube_thumb(){
			$retval = array(
				'success' => false
			);
			
			if(isset($_POST['url'])){
				$url = esc_url_raw($_POST['url']);
				if ($video_id = $this->_get_youtube_id($url) ) { // A youtube url
	
					$retval = array(
						'success' => true,
						'url' => 'http://img.youtube.com/vi/'.$video_id.'/default.jpg'
					);
					
				}
			}
			
			echo json_encode($retval);
			die();
		}
		
		/**
		* Ajax function for vimeo thumbnail
		*/
		function mtbxr_get_vimeo_thumb(){
			$retval = array(
				'success' => false
			);
			
			if(isset($_POST['url'])){
				$url = esc_url_raw($_POST['url']);
				if ( $vimeo_thumb = $this->_get_vimeo_thumb_uri($url) ) { // A vimeo url
					$retval = array(
						'success' => true,
						'url' => $vimeo_thumb
					);
				}
			}
			
			echo json_encode($retval);
			die();
		}
		
		function _get_vimeo_id($url){
			if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
				return false;
			}
			$parsed_url = parse_url($url);
			if ($parsed_url['host'] == 'vimeo.com'){
				$vimeo_id = ltrim( $parsed_url['path'], '/');
				if (is_numeric($vimeo_id)) {
					return $vimeo_id;
				}
			}
			return false;
		}
		function _get_vimeo_thumb_uri($url){
			if($vimeo_id = $this->_get_vimeo_id($url) ){
				$vimeo = unserialize( file_get_contents('http://vimeo.com/api/v2/video/'.$vimeo_id.'.php') );
				if( isset($vimeo[0]['thumbnail_medium']) ){
					return $vimeo[0]['thumbnail_medium'];
				}
			}
			return '';
		}
		
		/**
		* Return youtube video id from url. False on fail
		*/
		function _get_youtube_id($url){
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
		
		/**
		* Export admin menu
		*/
		function admin_menu(){
		
		}
		
		/**
		* Export page
		*/
		function export_render(){
			?>
			<div class="wrap mnm-wrapper mnm-minimal">
				<div id="icon-themes" class="icon32"><br /></div>
				<h2>Export Metaboxes</h2>
				<?php
				
				if($metaboxes = mtbxr_get_all_metaboxes()){
					$string = 'if(!defined("MTBXR_PATH")){
		function mtbxr_mytheme_metaboxes($metaboxes){
			return unserialize('.var_export( serialize($metaboxes), true ).');
		}
		add_filter("mtbxr_metaboxes", "mtbxr_mytheme_metaboxes");
		
		define("MTBXR_PATH", realpath(STYLESHEETPATH).DIRECTORY_SEPARATOR."inc".DIRECTORY_SEPARATOR."wp-metaboxer".DIRECTORY_SEPARATOR );
		define("MTBXR_URL", get_stylesheet_directory_uri()."/inc/wp-metaboxer/" );
		
		function mtbxr_show_builder(){}
		
		require_once("inc/wp-metaboxer/wp-metaboxer.php");
	}';
					?>
					<p>WP Metaboxer can be seamlessly integrated in your theme. To do this, follow these steps:</p>
					<ol>
						<li>Copy the <strong>wp-content/plugins/wp-metaboxer</strong> folder and its contents into an <strong>inc</strong> folder in your theme. Eg. wp-content/themes/twentyeleven/inc/wp-metaboxer/ </li>
						<li>Copy and paste the export code below into your functions.php</li>
						<li>Disable the WP Metaboxer plugin. That's it! Your metaboxes are now part of your theme.</li>
					</ol>
					<pre class="mtbxr-export widefat"><?php echo $string; ?></pre>
					<?php
				} else {
					?>
					<p>Nothing to export. No metaboxes found.</p>
					<?php
				}	
				?>
			</div>	
			<?php
		}
	
	} // end class
	
endif;