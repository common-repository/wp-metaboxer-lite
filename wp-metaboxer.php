<?php
/*
Plugin Name: WP Metaboxer Lite
Plugin URI: http://www.codefleet.net/wp-metaboxer/
Description: WP Metaboxer Lite is the free version of WP Metaboxer. Upgrade to the full version to take advantage of all the features.
Version: 1.3.0
Author: Nico Amarilla
Author URI: http://www.codefleet.net/
License:

  Copyright 2012 (kosinix@codefleet.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

if(!defined('MTBXR_VERSION')){
    define('MTBXR_VERSION', '1.3.0' );
}
if(!defined('MTBXR_DEBUG')){
    define('MTBXR_DEBUG', false );
}
if(!defined('MTBXR_PATH')){
	define('MTBXR_PATH', realpath(plugin_dir_path(__FILE__)).DIRECTORY_SEPARATOR );
}
if(!defined('MTBXR_URL')){
	define('MTBXR_URL', plugin_dir_url(__FILE__));// ..with trailing slash
}

$mtbxr_debug_info = null; //Store debug data here.

load_plugin_textdomain( 'mtbxr', false, 'wp-metaboxer/lang' );

require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'class-wp-metaboxer.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'class-mtbxr-view.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'class-mtbxr-builder.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'class-mtbxr-field.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-textbox.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-textarea.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-select.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-checkbox.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-radio.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-date.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-datetime.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-time.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-checkbox-group.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-sortable-images.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-sortable-files.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-sortable-textboxes.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-sortable-textareas.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-youtube.php');
require_once(MTBXR_PATH.'classes'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'class-mtbxr-vimeo.php');

// Allow overriding for the function that shows the builder
if(!function_exists('mtbxr_show_builder')) {
	function mtbxr_show_builder(){
		$mtbxr_builder = new Mtbxr_Builder();
	}
	mtbxr_show_builder();
}

/**
* Get all metaboxes
*/
function mtbxr_get_all_metaboxes(){
	$metaboxes = array();
	$args = array(
		'post_type'=>'mtbxr_metabox',
		'numberposts'=>-1
	);
	$posts = get_posts($args);
	if(!empty($posts)){
		foreach($posts as $i=>$post){
			$metaboxes[$i] = get_post_meta( $post->ID, 'mtbxr_metabox', true);
			$metaboxes[$i]['title'] = $post->post_title;
			$metaboxes[$i]['uid'] = $post->post_name;
		}
		$metaboxes = apply_filters('mtbxr_metaboxes', $metaboxes);
		return $metaboxes;
	}
	return false;
}



/**
* Create metaboxes
*/
function mtbxr_register_meta_boxes()
{
	if( $meta_box_data = mtbxr_get_all_metaboxes() ){

		if ( class_exists( 'WP_Metaboxer' ) and is_array($meta_box_data))
		{
			foreach ( $meta_box_data as $meta_box )
			{
				if(isset($meta_box['page']) && is_array($meta_box['page'])){
					foreach($meta_box['page'] as $i=>$page ){
						new WP_Metaboxer($meta_box, $page);
					}
				}
			}
		}
	}
}
add_action( 'init', 'mtbxr_register_meta_boxes' );

/**
* Utility function to get meta value
*/
function mtbxr_val($key, $post_id=null){
	global $post;
	
	if($post_id===null){
		$post_id = $post->ID;
	}
	
	return get_post_meta($post_id, $key, true);
}

/**
* Return meta array as simplified array
*/
function mtbxr_all($post_id=null){
	global $post;
	
	if($post_id===null){
		$post_id = $post->ID;
	}
	$simplified_meta = array();
	if($metas = get_post_custom($post_id)){
		foreach($metas as $key=>$meta){
			$simplified_meta[$key] = maybe_unserialize($meta[0]);
		}
		return $simplified_meta;
	}
	return false;
}

/**
* print_r with a twist
*/
function mtbxr_debug($s){
	return '<pre class="mtbxr-debug">'.print_r($s,1).'</pre>';
}

/**
* print_r with debug styles
*/
function mtbxr_debug_details(){
	global $mtbxr_debug_info;
	$out = '<div class="mtbxr-debug-details">';
	if(!empty($mtbxr_debug_info)){
		foreach($mtbxr_debug_info as $debug_info){
			$out .= '<p>'.$debug_info.'</p>';
		}
	}
	$out .= '</div>';
	return $out;
}

/**
* Provides a way to easily filter the field note
*
* Here we append our meta unique key string on field's note
*/
function mtbxr_field_notes( $note, $field ) {

	$note .= ' Meta Key: '.esc_attr($field['uid']);

	return $note;
}
add_filter( 'mtbxr_field_note', 'mtbxr_field_notes', 10, 2 );


/**
* Utility function for displaying an embedded video
*/
function mtbxr_video( $key, $index=0, $width=null, $height=null, $post_id=null ){
	global $post;
	
	if($post_id===null){
		$post_id = $post->ID;
	}
	$videos = mtbxr_val($key, $post_id); // Return arrays of vids
	if( is_array($videos) and isset($videos[$index]) ){
		if( $width && $height ){
			return wp_oembed_get( $videos[$index], array('width'=> (int)$width, 'height'=> (int)$height) );
		}
		return wp_oembed_get( $videos[$index] );
	}
	return false;
}

/**
* Utility function for displaying embedded videos
*/
function mtbxr_videos( $key, $width=null, $height=null, $post_id=null ){
	global $post;
	
	if($post_id===null){
		$post_id = $post->ID;
	}
	$videos = mtbxr_val($key, $post_id); // Return arrays of vids
	if( is_array($videos) ){
		foreach($videos as $i=>$video){
			if( $width && $height ){
				$videos[$i] = wp_oembed_get( $video, array('width'=> (int)$width, 'height'=> (int)$height) );
			} else {
				$videos[$i] = wp_oembed_get( $video );
			}
		}
		return $videos;
	}
	return false;
}

