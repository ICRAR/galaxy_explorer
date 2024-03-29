<?php
/*
Plugin Name: MaxGalleria
Plugin URI: http://maxgalleria.com
Description: The gallery platform for WordPress.
Version: 4.08
Author: Max Foundry
Author URI: http://maxfoundry.com

Copyright 2014 Max Foundry, LLC (http://maxfoundry.com)
*/

class MaxGalleria {
	private $_addons;
	
	public $admin;
	public $common;
	public $meta;
	public $nextgen;
	public $settings;
	public $shortcode;
	public $shortcode_thumb;
	public $new_gallery;
	public $image_gallery;
	public $video_gallery;
	public $gallery_widget;
	public $gallery_thumb_widget;
	
	public function __construct() {
		$this->_addons = array();
		
		$this->set_global_constants();
		$this->set_activation_hooks();
		$this->initialize_properties();
		$this->add_thumb_sizes();
		$this->setup_hooks();
		$this->register_media_sources();
		$this->register_templates();
    $this->register_media_library();
	}
	
	function activate() {
		update_option(MAXGALLERIA_VERSION_KEY, MAXGALLERIA_VERSION_NUM);
		
    $this->copy_template();

    //add_action('wp_head', 'create_promo_js');
	}
  
  function copy_template() {
    
    $fh = fopen("maxgalleria.txt", "w");
    $date = date('m/d/Y h:i:s a', time());
    
		// Copy gallery post type template file to theme directory
    $source = MAXGALLERIA_PLUGIN_DIR . '/single-maxgallery.php';
    $destination = $this->get_theme_dir() . '/single-maxgallery.php';
    if(!defined('PRESERVE_MAXGALLERIA_TEMPLATE')) {
      if(copy($source, $destination))
        fwrite ($fh, "$date - copy to $destination successful\n");
      else
        fwrite ($fh, "$date - copy to $destination failed\n");
    }  
    else if(!file_exists($destination)) {
      if(copy($source, $destination))
        fwrite ($fh, "$date - copy to $destination successful\n");
      else
        fwrite ($fh, "$date - copy to $destination failed\n");
    }
		flush_rewrite_rules();    
    fwrite ($fh, "$date - permalinks updated\n");
		fclose($fh);
  }
	
	public function add_thumb_sizes() {
		// In addition to the thumbnail support when registering the custom post type, we need to add theme support
		// to properly handle the featured image for a gallery, just in case the theme itself doesn't have it.
		add_theme_support('post-thumbnails');
		
		// Additional sizes, cropped
		add_image_size(MAXGALLERIA_META_IMAGE_THUMB_SMALL, 100, 100, true);
		add_image_size(MAXGALLERIA_META_IMAGE_THUMB_MEDIUM, 150, 150, true);
		add_image_size(MAXGALLERIA_META_IMAGE_THUMB_LARGE, 200, 200, true);
		add_image_size(MAXGALLERIA_META_VIDEO_THUMB_SMALL, 150, 100, true);
		add_image_size(MAXGALLERIA_META_VIDEO_THUMB_MEDIUM, 200, 133, true);
		add_image_size(MAXGALLERIA_META_VIDEO_THUMB_LARGE, 250, 166, true);
	}

	public function admin_page_is_maxgallery_post_type($post_id = 0) {
		global $post;
		global $post_type;
		
		if (isset($post_id) && $post_id > 0 && get_post_type($post_id) == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($_GET['post']) && $_GET['post'] > 0 && get_post_type($_GET['post']) == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($_GET['post_type']) && $_GET['post_type'] == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($post_type) && $post_type == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($post) && $post->post_type == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		return false;
	}
	
	public function admin_page_is_media_edit() {
		if ($this->common->url_contains('wp-admin/media.php') && $this->common->url_contains('action=edit')) {
			return true;
		}
		
		return false;
	}
	
	public function admin_page_is_post_edit() {
		if ($this->common->url_contains('wp-admin/post.php') && $this->common->url_contains('action=edit')) {
			return true;
		}
		
		return false;
	}
	
	public function call_function_for_each_site($function) {
		global $wpdb;
		
		// Hold this so we can switch back to it
		$current_blog = $wpdb->blogid;
		
		// Get all the blogs/sites in the network and invoke the function for each one
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blog_ids as $blog_id) {
			switch_to_blog($blog_id);
			call_user_func($function);
		}
		
		// Now switch back to the root blog
		switch_to_blog($current_blog);
	}
	
	public function create_gallery_columns($column) {
		// The Title and Date columns are standard, so we don't have to explicitly provide output for them
		
		global $post;
		$maxgallery = new MaxGalleryOptions($post->ID);

		// Get all the attachments (the -1 gets all of them)
		$args = array('post_parent' => $post->ID, 'post_type' => 'attachment', 'orderby' => 'menu_order', 'order' => 'asc', 'numberposts' => -1);
		$attachments = get_posts($args);
		
		// Rounded borders
		$style = 'border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px;';
		
		switch ($column) {
			case 'type':
				if ($maxgallery->is_image_gallery()) {
					echo '<img src="' . MAXGALLERIA_PLUGIN_URL . '/images/image-32.png" alt="' . __('Image', 'maxgalleria') . '" title="' . __('Image', 'maxgalleria') . '" style="' . $style . '" />';
				}
				
				if ($maxgallery->is_video_gallery()) {
					echo '<img src="' . MAXGALLERIA_PLUGIN_URL . '/images/video-32.png" alt="' . __('Video', 'maxgalleria') . '" title="' . __('Video', 'maxgalleria') . '" style="' . $style . '" />';
				}
				
				break;
			case 'thumbnail':
				if (has_post_thumbnail($post->ID)) {
					echo get_the_post_thumbnail($post->ID, array(32, 32), array('style' => $style));
				}
				else {
					// Show the first thumb
					foreach ($attachments as $attachment) {
						$no_media_icon = 0;
						echo wp_get_attachment_image($attachment->ID, array(32, 32), $no_media_icon, array('style' => $style));
						break;
					}
				}
				break;
			case 'template':
				$template_key = $maxgallery->get_template();
				echo $this->get_template_name($template_key);
				break;
			case 'number':
				if ($maxgallery->is_image_gallery()) {
					if (count($attachments) == 0) { _e('0 images', 'maxgalleria'); }
					if (count($attachments) == 1) { _e('1 image', 'maxgalleria'); }
					if (count($attachments) > 1) { printf(__('%d images', 'maxgalleria'), count($attachments)); }
				}

				if ($maxgallery->is_video_gallery()) {
					if (count($attachments) == 0) { _e('0 videos', 'maxgalleria'); }
					if (count($attachments) == 1) { _e('1 video', 'maxgalleria'); }
					if (count($attachments) > 1) { printf(__('%d videos', 'maxgalleria'), count($attachments)); }
				}
				
				break;
			case 'shortcode':
				echo '[maxgallery id="' . $post->ID . '"]';
				
				if ($post->post_status == 'publish') {
					echo '<br />';
					echo '[maxgallery name="' . $post->post_name . '"]';
				}
				
				break;
		}
	}
	
	public function create_plugin_action_links($links, $file) {
		static $this_plugin;
		
		if (!$this_plugin) {
			$this_plugin = plugin_basename(__FILE__);
		}
		
		if ($file == $this_plugin) {
			$settings_link = '<a href="' . admin_url() . 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE . '&page=maxgalleria-settings">' . __('Settings', 'maxgalleria') . '</a>';
			array_unshift($links, $settings_link);
			
			$galleries_link = '<a href="' . admin_url() . 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE . '">' . __('Galleries', 'maxgalleria') . '</a>';
			array_unshift($links, $galleries_link);
		}

		return $links;
	}
	
	public function create_sortable_gallery_columns($vars) {
		if (isset($vars['orderby'])) {
			switch ($vars['orderby']) {
				case 'type':
					$vars = array_merge($vars, array('meta_key' => 'maxgallery_type', 'orderby' => 'meta_value'));
					break;
				case 'template':
					$vars = array_merge($vars, array('meta_key' => 'maxgallery_template', 'orderby' => 'meta_value'));
					break;
			}
		}
		
		return $vars;
	}
	
	function deactivate() {
		delete_option(MAXGALLERIA_VERSION_KEY);
		
    if(!defined('PRESERVE_MAXGALLERIA_TEMPLATE')) {
      // Delete the gallery post type template file from the theme directory
      $file = $this->get_theme_dir() . '/single-maxgallery.php';
      unlink($file);
    }
		
		flush_rewrite_rules();
	}
	
	public function define_gallery_columns($columns) {
		$columns = apply_filters(MAXGALLERIA_FILTER_GALLERY_COLUMNS, array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title', 'maxgalleria'),
			'thumbnail' => __('Thumbnail', 'maxgalleria'),
			'type' => __('Type', 'maxgalleria'),
			'template' => __('Template', 'maxgalleria'),
			'number' => __('Number of Media', 'maxgalleria'),
			'shortcode' => __('Shortcode', 'maxgalleria'),
			'date' => __('Date', 'maxgalleria')
		));
		
		return $columns;
	}
	
	public function define_sortable_gallery_columns($columns) {		
		// Title and Date are sortable by default

		$columns['type'] = 'type';
		$columns['template'] = 'template';
		$columns['number'] = 'number';
		
		return $columns;
	}
	
	public function do_activation($network_wide) {
		if ($network_wide) {
			$this->call_function_for_each_site(array($this, 'activate'));
		}
		else {
			$this->activate();
		}
	}
	
	public function do_deactivation($network_wide) {	
		if ($network_wide) {
			$this->call_function_for_each_site(array($this, 'deactivate'));
		}
		else {
			$this->deactivate();
		}
	}
	
	public function enqueue_admin_print_scripts() {
		if ($this->admin_page_is_maxgallery_post_type()) {
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
			
			// For the media uploader
			wp_enqueue_media();
			wp_enqueue_script('maxgalleria-media-script', MAXGALLERIA_PLUGIN_URL . '/js/media.js', array('jquery'));

			// Other stuff
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('maxgalleria-datatables', MAXGALLERIA_PLUGIN_URL . '/libs/datatables/jquery.dataTables.min.js', array('jquery'));
			wp_enqueue_script('maxgalleria-datatables-row-reordering', MAXGALLERIA_PLUGIN_URL . '/libs/datatables/jquery.dataTables.rowReordering.js', array('jquery'));      
			//wp_enqueue_script('maxgalleria-fancybox', MAXGALLERIA_PLUGIN_URL . '/libs/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'));
			wp_enqueue_script('maxgalleria-easing', MAXGALLERIA_PLUGIN_URL . '/libs/fancybox/jquery.easing-1.3.pack.js', array('jquery'));
			wp_enqueue_script('maxgalleria-simplemodal', MAXGALLERIA_PLUGIN_URL . '/libs/simplemodal/jquery.simplemodal-1.4.3.min.js', array('jquery'));
			wp_enqueue_script('maxgalleria-magnific', MAXGALLERIA_PLUGIN_URL . '/libs/magnific/jquery.magnific-popup.min.js', array('jquery'));
            
      $screen = get_current_screen();
      if($screen->id == 'edit-maxgallery') {                
        wp_enqueue_script('maxgalleria-promo', MAXGALLERIA_PLUGIN_URL . '/js/promo.js', array('jquery'));                                    
        wp_localize_script( 'maxgalleria-promo', 'mg_promo', 
              array( 'pluginurl' => MAXGALLERIA_PLUGIN_URL));         
      }
		}
	}

	public function enqueue_admin_print_styles() {		
		if ($this->admin_page_is_maxgallery_post_type()) {
			wp_enqueue_style('thickbox');
			wp_enqueue_style('maxgalleria-jquery-ui', MAXGALLERIA_PLUGIN_URL . '/libs/jquery-ui/jquery-ui.css');
			//wp_enqueue_style('maxgalleria-fancybox', MAXGALLERIA_PLUGIN_URL . '/libs/fancybox/jquery.fancybox-1.3.4.css');
			wp_enqueue_style('maxgalleria-simplemodal', MAXGALLERIA_PLUGIN_URL . '/libs/simplemodal/simplemodal.css');
			wp_enqueue_style('maxgalleria-magnific', MAXGALLERIA_PLUGIN_URL . '/libs/magnific/magnific-popup.css');
			wp_enqueue_style('maxgalleria', MAXGALLERIA_PLUGIN_URL . '/maxgalleria.css');
      wp_enqueue_style('foundation', MAXGALLERIA_PLUGIN_URL . '/libs/foundation/foundation.min.css');
      
		}
	}
	  
	public function get_all_addons() {
		return $this->_addons;
	}

	public function get_media_source_addons() {
		$media_source_addons = array();
		
		foreach ($this->_addons as $addon) {
			if ($addon['type'] == 'media_source') {
				array_push($media_source_addons, $addon);
			}
		}
		
		return $media_source_addons;
	}
	
	public function get_template_addons() {
		$template_addons = array();
		
		foreach ($this->_addons as $addon) {
			if ($addon['type'] == 'template') {
				array_push($template_addons, $addon);
			}
		}
		
		return $template_addons;
	}
	
	public function get_template_name($template_key) {
		$template_name = '';
		$templates = $this->get_template_addons();
		
		foreach ($templates as $template) {
			if ($template['key'] == $template_key) {
				$template_name = $template['name'];
				break;
			}
		}
		
		return $template_name;
	}
	
	public function get_theme_dir() {
    if(is_child_theme())
		  return ABSPATH . 'wp-content/themes/' . get_stylesheet();
    else
		  return ABSPATH . 'wp-content/themes/' . get_template();
	}
	
	public function hide_add_new() {
		global $submenu;
		unset($submenu['edit.php?post_type=' . MAXGALLERIA_POST_TYPE][10]);
	}
	
	public function initialize_properties() {
		// The order doesn't really matter, except maxgallery-options.php must be included first so
		// that the MaxGalleryOptions class can be created in other parts of the system as needed
		
		require_once 'maxgallery-options.php';
		require_once 'maxgalleria-admin.php';
		require_once 'maxgalleria-common.php';
		require_once 'maxgalleria-meta.php';
		require_once 'maxgalleria-nextgen.php';
		require_once 'maxgalleria-settings.php';
		require_once 'maxgalleria-shortcode.php';
		require_once 'maxgalleria-shortcode-thumb.php';
		require_once 'maxgalleria-new-gallery.php';
		require_once 'maxgalleria-image-gallery.php';
		require_once 'maxgalleria-video-gallery.php';
		require_once 'widgets/gallery-widget.php';
		require_once 'widgets/gallery-thumb-widget.php';
		
		$this->admin = new MaxGalleriaAdmin();
		$this->common = new MaxGalleriaCommon();
		$this->meta = new MaxGalleriaMeta();
		$this->nextgen = new MaxGalleriaNextGen();
		$this->settings = new MaxGalleriaSettings();
		$this->shortcode = new MaxGalleriaShortcode();
		$this->shortcode_thumb = new MaxGalleriaShortcodeThumb();
		$this->new_gallery = new MaxGalleriaNewGallery();
		$this->image_gallery = new MaxGalleriaImageGallery();
		$this->video_gallery = new MaxGalleriaVideoGallery();
		$this->gallery_widget = new MaxGalleriaGalleryWidget();
		$this->gallery_thumb_widget = new MaxGalleriaGalleryThumbWidget();
	}
	
	public function load_textdomain() {
		load_plugin_textdomain('maxgalleria', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
	
	public function media_button($context) {
		global $pagenow, $wp_version;
		$output = '';

		// Only run in post/page creation and edit screens
		if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
			$title = __('Add Gallery', 'maxgalleria');
			$icon = MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-16.png';
			$img = '<span class="wp-media-buttons-icon" style="background-image: url(' . $icon . '); width: 16px; height: 16px; margin-top: 1px;"></span>';
			$output = '<a href="#TB_inline?width=640&inlineId=select-maxgallery-container" class="thickbox button" title="' . $title . '" style="padding-left: .4em;">' . $img . ' ' . $title . '</a>';
		}

		return $context . $output;
	}
	
	public function media_button_admin_footer() {
		require_once 'maxgalleria-media-button.php';
	}
	
	public function register_gallery_post_type() {
		$slug = $this->settings->get_rewrite_slug();
		$exclude_from_search = $this->settings->get_exclude_galleries_from_search();
		$exclude_from_search = $exclude_from_search == 'on' ? true : false;
		
		$labels = apply_filters(MAXGALLERIA_FILTER_GALLERY_POST_TYPE_LABELS, array(
			'name' => __('Galleries', 'maxgalleria'),
			'singular_name' => __('Gallery', 'maxgalleria'),
			'add_new' => __('Add New', 'maxgalleria'),
			'add_new_item' => __('Add New Gallery', 'maxgalleria'),
			'edit_item' => __('Edit Gallery', 'maxgalleria'),
			'new_item' => __('New Gallery', 'maxgalleria'),
			'view_item' => __('View Gallery', 'maxgalleria'),
			'search_items' => __('Search Galleries', 'maxgalleria'),
			'not_found' => __('No galleries found', 'maxgalleria'),
			'not_found_in_trash' => __('No galleries found in trash', 'maxgalleria'),
			'parent_item_colon' => ''
		));
		
		$args = apply_filters(MAXGALLERIA_FILTER_GALLERY_POST_TYPE_ARGS, array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'menu_icon' => MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-16.png',
			'rewrite' => array('slug' => $slug),
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'thumbnail'),
			'taxonomies' => array('category', 'post_tag'),
			'exclude_from_search' => $exclude_from_search
		));
		
		register_post_type(MAXGALLERIA_POST_TYPE, $args);
	}
	
	public function register_addon($addon) {
		array_push($this->_addons, $addon);
	}
	
	public function register_media_sources() {
		// YouTube
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/media-sources/youtube/youtube.php';
		$youtube = new MaxGalleriaYouTube();
		$youtube_addon = array(
			'key' => $youtube->addon_key,
			'name' => $youtube->addon_name,
			'type' => $youtube->addon_type,
			'subtype' => $youtube->addon_subtype,
			'settings' => $youtube->addon_settings
		);
		$this->register_addon($youtube_addon);
	}
 
	public function register_templates() {
		// Image Tiles template
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/templates/image-tiles/image-tiles.php';
		$image_tiles = new MaxGalleriaImageTiles();
		$image_tiles_addon = array(
			'key' => $image_tiles->addon_key,
			'name' => $image_tiles->addon_name,
			'type' => $image_tiles->addon_type,
			'subtype' => $image_tiles->addon_subtype,
			'settings' => $image_tiles->addon_settings,
			'image' => $image_tiles->addon_image,
			'output' => $image_tiles->addon_output
		);
		$this->register_addon($image_tiles_addon);
		
		// Video Tiles template
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/templates/video-tiles/video-tiles.php';
		$video_tiles = new MaxGalleriaVideoTiles();
		$video_tiles_addon = array(
			'key' => $video_tiles->addon_key,
			'name' => $video_tiles->addon_name,
			'type' => $video_tiles->addon_type,
			'subtype' => $video_tiles->addon_subtype,
			'settings' => $video_tiles->addon_settings,
			'image' => $video_tiles->addon_image,
			'output' => $video_tiles->addon_output
		);
		$this->register_addon($video_tiles_addon);
	}

  public function register_media_library() {
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/media-library/media-library.php';    
    $maxgalleria_media_library = new MaxGalleriaMediaLib();    
    
		$media_library_addon = array(
			'key' => $maxgalleria_media_library->addon_key,
			'name' => $maxgalleria_media_library->addon_name,
			'type' => $maxgalleria_media_library->addon_type,
			'subtype' => $maxgalleria_media_library->addon_subtype,
			'settings' => $maxgalleria_media_library->addon_settings
		);
		$this->register_addon($media_library_addon);
        
  }
  
	public function register_widgets() {
		register_widget('MaxGalleriaGalleryWidget');
		register_widget('MaxGalleriaGalleryThumbWidget');
	}
	
	public function set_activation_hooks() {
		register_activation_hook(__FILE__, array($this, 'do_activation'));
		register_deactivation_hook(__FILE__, array($this, 'do_deactivation'));
	}
	
	public function set_global_constants() {	
		define('MAXGALLERIA_VERSION_KEY', 'maxgalleria_version');
		define('MAXGALLERIA_VERSION_NUM', '4.08');
		define('MAXGALLERIA_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
		define('MAXGALLERIA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MAXGALLERIA_PLUGIN_NAME);
		define('MAXGALLERIA_PLUGIN_URL', plugin_dir_url('') . MAXGALLERIA_PLUGIN_NAME);
		define('MAXGALLERIA_POST_TYPE', 'maxgallery');
		define('MAXGALLERIA_SETTINGS', admin_url() . 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE . '&page=maxgalleria-settings');
		define('MAXGALLERIA_META_IMAGE_THUMB_SMALL', 'maxgallery-meta-image-thumb-small');
		define('MAXGALLERIA_META_IMAGE_THUMB_MEDIUM', 'maxgallery-meta-image-thumb-medium');
		define('MAXGALLERIA_META_IMAGE_THUMB_LARGE', 'maxgallery-meta-image-thumb-large');
		define('MAXGALLERIA_META_VIDEO_THUMB_SMALL', 'maxgallery-meta-video-thumb-small');
		define('MAXGALLERIA_META_VIDEO_THUMB_MEDIUM', 'maxgallery-meta-video-thumb-medium');
		define('MAXGALLERIA_META_VIDEO_THUMB_LARGE', 'maxgallery-meta-video-thumb-large');
		define('MAXGALLERIA_THUMB_SHAPE_LANDSCAPE', 'landscape');
		define('MAXGALLERIA_THUMB_SHAPE_PORTRAIT', 'portrait');
		define('MAXGALLERIA_THUMB_SHAPE_SQUARE', 'square');
		define('MAXGALLERIA_SETTING_REWRITE_SLUG', 'maxgalleria_setting_rewrite_slug');
		define('MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH', 'maxgalleria_setting_exlude_galleries_from_search');
		define('MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE', 'maxgalleria_setting_default_image_gallery_template');
		define('MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE', 'maxgalleria_setting_default_video_gallery_template');
    define('MAXGALLERIA_ADMIN_NOTICE', 'maxgalleria_admin_notice-2');
    //define('NO_MEDIA_LIBRARY_EXTENDED', true);
		
		// Bring in all the actions and filters
		require_once 'maxgalleria-hooks.php';
	}
	
	public function set_icon_edit_image() {
		if ($this->admin_page_is_maxgallery_post_type()) {
			echo '<style>';
			echo '#icon-edit { background: url("'. MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-32.png' . '") no-repeat transparent; }';
			echo '</style>';
		}
	}
	
	public function setup_hooks() {
		add_action('init', array($this, 'load_textdomain'));
		add_action('init', array($this, 'register_gallery_post_type'));
		add_action('init', array($this, 'display_mg_admin_notice'));
		add_filter('plugin_action_links', array($this, 'create_plugin_action_links'), 10, 2);
		add_action('admin_print_scripts', array($this, 'enqueue_admin_print_scripts'));
		add_action('admin_print_styles', array($this, 'enqueue_admin_print_styles'));
		add_action('admin_head', array($this, 'set_icon_edit_image'));
		add_action('admin_menu', array($this, 'hide_add_new'));
		add_filter('manage_edit-' . MAXGALLERIA_POST_TYPE . '_columns', array($this, 'define_gallery_columns'));
		add_filter('manage_edit-' . MAXGALLERIA_POST_TYPE . '_sortable_columns', array($this, 'define_sortable_gallery_columns'));
		add_action('manage_posts_custom_column', array($this, 'create_gallery_columns'));
		add_filter('request', array($this, 'create_sortable_gallery_columns'));
		add_filter('media_upload_tabs', array($this, 'set_media_upload_tabs'), 50, 1);
		add_filter('media_view_strings', array($this, 'set_media_view_strings'), 50, 1);
		add_filter('post_mime_types', array($this, 'set_post_mime_types'), 50, 1);
		add_filter('upload_mimes', array($this, 'set_upload_mimes'), 50, 1);
		add_action('media_buttons_context', array($this, 'media_button'));
		add_action('admin_footer', array($this, 'media_button_admin_footer'));
		add_action('widgets_init', array($this, 'register_widgets'));
		add_action('after_switch_theme', array($this, 'copy_template'));
    
    if(!defined('ATTACHMENT_QUERY_OFF'))    
      add_action( 'pre_get_posts', array($this, 'modify_attachments'));

//    this is not working yet:    
//    check daily for the template in the theme folder; copy and update permalinks if missing.        
//    if ( ! wp_next_scheduled( 'mg_task_hook' ) ) {
//      wp_schedule_event( time(), 'daily', 'mg_task_hook' );
//    }
//
//    add_action( 'mg_task_hook', array($this, 'mg_daily_check') );
             
	}

  public function modify_attachments( $query ) {
    
    if ( is_admin() && strpos( $_SERVER[ 'REQUEST_URI' ], 'admin-ajax.php' ) !== false && $_REQUEST['action'] === 'query-attachments'  ) {      
      add_filter( 'posts_groupby', array($this, 'group_attachments') );
    }
    return $query;
  }
    
  public function group_attachments($groupby) {  
    if ($groupby != '') {
      $groupby .= " , guid";
    } else {
      $groupby .= " guid";
    }
    return $groupby;    
  }
      
	public function set_media_upload_tabs($tabs) {
		// Remove the "From URL", "Gallery", and "NextGEN" tabs from the media library popup.
		// Only the tabs "From Computer" and "Media Library" should be shown.

		if ($this->admin_page_is_maxgallery_post_type()) {
			unset($tabs['type_url']);	// From URL tab
			unset($tabs['gallery']);	// Gallery tab
			unset($tabs['nextgen']);	// NextGEN tab
		}
		
		return $tabs;
	}
	
	public function set_media_view_strings($strings) {
		if ($this->admin_page_is_maxgallery_post_type()) {
			// Remove these
			unset($strings['insertFromUrlTitle']);
			unset($strings['setFeaturedImageTitle']);
			unset($strings['createGalleryTitle']);
			unset($strings['createPlaylistTitle']);
			unset($strings['createVideoPlaylistTitle']);
			
			// Change these for better context in MaxGalleria galleries
			$strings['insertMediaTitle'] = __('Add Images', 'maxgalleria');
			$strings['insertIntoPost'] = __('Add to Gallery', 'maxgalleria');
			$strings['uploadedToThisPost'] = __('Added to this gallery', 'maxgalleria');
		}
		
		return $strings;
	}
	
	public function set_post_mime_types($mime_types) {
		// Remove the video and audio types
		
		if ($this->admin_page_is_maxgallery_post_type()) {
			unset($mime_types['video']);
			unset($mime_types['audio']);
		}
		
		return $mime_types;
	}
	
	public function set_upload_mimes($mime_types) {
		// Only allow image file type uploads. The complete list allowed by WordPress is
		// located in the get_allowed_mime_types() function in wp-includes/functions.php.
		
		if ($this->admin_page_is_maxgallery_post_type()) {
			$mime_types = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
				'bmp' => 'image/bmp',
				'tif/tiff' => 'image/tiff'
			);
		}
		
		return $mime_types;
	}
	
	public function thickbox_l10n_fix() {
		// When combining scripts, localization is lost for thickbox.js, so we call this
		// function to fix it. See http://wordpress.org/support/topic/plugin-affecting-photo-galleriessliders
		// for more details.
		echo '<script type="text/javascript">';
		echo "var thickboxL10n = " . json_encode(array(
			'next' => __('Next >'),
			'prev' => __('< Prev'),
			'image' => __('Image'),
			'of' => __('of'),
			'close' => __('Close'),
			'noiframes' => __('This feature requires inline frames. You have iframes disabled or your browser does not support them.'),
			'loadingAnimation' => includes_url('js/thickbox/loadingAnimation.gif'),
			'closeImage' => includes_url('js/thickbox/tb-close.png')));
		echo '</script>';
	}
  
  public function display_mg_admin_notice () {
        
    $current_user_id = get_current_user_id(); 

    $notice = get_user_meta( $current_user_id, MAXGALLERIA_ADMIN_NOTICE, true );
    if( $notice !== 'off' )
      add_action( 'admin_notices', array($this, 'mg_admin_notice' ));      
  }
  
  public function mg_admin_notice() {
   if( current_user_can( 'manage_options' ) ) {  ?>
      <div class="update-nag">         
          <p><?php _e( 'Version 4.05 of Maxgalleria includes Media Library Plus for organizing your images into folders. <a href="http://maxgalleria.com/media-library-plus/" target="_blank">Click here to learn more.</a>' ); ?></p>
          <!--<p><?php _e( 'Versions 3.1.0 and higher of Maxgalleria include Magnific Popup as part of the plugin.  There is nothing to install.  Magnific Popup has many more options so please check your galleries. The <a href="http://maxgalleria.com/documentation/maxgalleria/quickstart/" target="_blank">MaxGalleria Quick Start Page</a> shows how to use these options.  If you are using the Image Carousel Add-on it must be updated to work with these versions of Maxgalleria.', 'maxgalleria' ); ?></p>-->
          <p><a href="<?php echo admin_url() . 'edit.php?post_type=maxgallery&page=mg-admin-notice'; ?>">Dismiss</a></p>
      </div>
    <?php     
    }
  }
  
  private function mg_daily_check() {

    $source = MAXGALLERIA_PLUGIN_DIR . '/single-maxgallery.php';
    $destination = $this->get_theme_dir() . '/single-maxgallery.php';
    
    if(!file_exists($destination)) {
      copy($source, $destination);
		  flush_rewrite_rules();
    }
		
  }
    
}

// Let's get this party started
$maxgalleria = new MaxGalleria();
?>